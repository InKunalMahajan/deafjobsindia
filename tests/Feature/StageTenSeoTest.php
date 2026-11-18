<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageTenSeoTest extends TestCase
{
    use RefreshDatabase;

    private function employer(bool $verified = true): Employer
    {
        return Employer::create([
            'name' => 'Example Employer',
            'slug' => 'example-employer',
            'verified' => $verified,
        ]);
    }

    private function job(Employer $employer, string $slug, array $extra = []): JobListing
    {
        return JobListing::create(array_merge([
            'employer_id' => $employer->id,
            'slug' => $slug,
            'title' => 'Example vacancy',
            'location' => 'Pune',
            'employment_type' => 'full-time',
            'work_mode' => 'onsite',
            'sector' => 'private',
            'summary' => 'Vacancy summary',
            'description' => 'Detailed vacancy description',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $extra));
    }

    private function article(string $slug, array $extra = []): Article
    {
        return Article::create(array_merge([
            'slug' => $slug,
            'title' => 'Article '.$slug,
            'description' => 'Article summary',
            'category' => 'Career',
            'body' => 'Article body',
            'status' => 'published',
            'is_demo' => false,
            'published_at' => now()->subDay(),
        ], $extra));
    }

    public function test_sitemap_excludes_unpublished_and_expired_jobs(): void
    {
        $employer = $this->employer();
        $this->job($employer, 'open-vacancy');
        $this->job($employer, 'expired-vacancy', ['deadline' => today()->subDay()]);
        $this->job($employer, 'draft-vacancy', ['status' => 'draft']);

        $this->get(route('sitemap'))->assertOk()
            ->assertSee(route('jobs.show', 'open-vacancy'))
            ->assertDontSee(route('jobs.show', 'expired-vacancy'))
            ->assertDontSee(route('jobs.show', 'draft-vacancy'));
    }

    public function test_sitemap_excludes_demo_future_articles_and_unverified_employers(): void
    {
        $this->article('visible');
        $this->article('demo', ['is_demo' => true]);
        $this->article('future', ['published_at' => now()->addDay()]);
        Employer::create(['name' => 'Hidden Employer', 'slug' => 'hidden-employer', 'verified' => false]);

        $this->get(route('sitemap'))->assertOk()
            ->assertSee(route('news.show', 'visible'))
            ->assertDontSee(route('news.show', 'demo'))
            ->assertDontSee(route('news.show', 'future'))
            ->assertDontSee(route('employers.show', 'hidden-employer'));
    }

    public function test_open_job_has_canonical_and_jobposting_jsonld(): void
    {
        $this->job($this->employer(), 'structured');
        $this->get(route('jobs.show', 'structured'))->assertOk()
            ->assertSee('rel="canonical"', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('JobPosting');
    }

    public function test_expired_job_remains_readable_without_active_jobposting(): void
    {
        $this->job($this->employer(), 'expired', ['deadline' => today()->subDay()]);
        $this->get(route('jobs.show', 'expired'))->assertOk()
            ->assertSee('noindex,follow')
            ->assertDontSee('JobPosting');
    }

    public function test_article_has_canonical_and_structured_metadata(): void
    {
        $this->article('structured-article');
        $this->get(route('news.show', 'structured-article'))->assertOk()
            ->assertSee('rel="canonical"', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('Article');
    }

    public function test_search_page_is_noindex_and_linked_from_navigation(): void
    {
        $this->get(route('search'))->assertOk()
            ->assertSee('noindex,follow')
            ->assertSee('Search articles');
    }

    public function test_remote_job_does_not_claim_unsupported_country_eligibility(): void
    {
        $this->job($this->employer(), 'remote-job', ['work_mode' => 'remote']);

        $this->get(route('jobs.show', 'remote-job'))->assertOk()
            ->assertDontSee('JobPosting')
            ->assertDontSee('applicantLocationRequirements');
    }

    public function test_job_with_invalid_application_url_has_no_jobposting_schema(): void
    {
        $this->job($this->employer(), 'unsafe-link', ['apply_url' => 'http://example.org/apply']);

        $this->get(route('jobs.show', 'unsafe-link'))->assertOk()
            ->assertDontSee('JobPosting');
    }

    public function test_tracking_parameters_do_not_force_noindex(): void
    {
        $this->get(route('home', ['utm_source' => 'newsletter']))->assertOk()
            ->assertDontSee('noindex,follow');
    }

    public function test_filter_parameters_keep_noindex(): void
    {
        $this->get(route('jobs.index', ['q' => 'assistant']))->assertOk()
            ->assertSee('noindex,follow');
    }

    public function test_robots_endpoint_uses_plain_text_and_disallows_admin(): void
    {
        $this->get(route('robots'))->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /admin/');
    }
}
