<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageNineAdminOperationsTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function employer(bool $verified = false): Employer
    {
        $number = Employer::count() + 1;
        return Employer::create([
            'name' => 'Company '.$number,
            'slug' => 'company-'.$number,
            'verified' => $verified,
        ]);
    }

    private function job(Employer $employer, string $slug, array $extra = []): JobListing
    {
        return JobListing::create(array_merge([
            'employer_id' => $employer->id,
            'slug' => $slug,
            'title' => 'Position '.$slug,
            'location' => 'Pune',
            'employment_type' => 'full-time',
            'work_mode' => 'remote',
            'sector' => 'private',
            'summary' => 'Job summary',
            'description' => 'Job description',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $extra));
    }

    public function test_dashboard_shows_counts_for_jobs_articles_and_employer_reviews(): void
    {
        $reviewed = $this->employer(true);
        $this->employer(false);
        $this->job($reviewed, 'open');
        $this->job($reviewed, 'draft', ['status' => 'draft']);
        Article::create([
            'slug' => 'draft-notice',
            'title' => 'Draft notice',
            'description' => 'Draft description',
            'category' => 'Career',
            'body' => 'Draft body',
            'status' => 'draft',
        ]);

        $this->actingAs($this->user('admin'))->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Unverified employers')
            ->assertSee('Articles in draft')
            ->assertSee('Jobs needing application updates')
            ->assertSee('Open jobs');
    }

    public function test_employer_list_includes_job_counts_without_deleting_relationships(): void
    {
        $employer = $this->employer(false);
        $this->job($employer, 'linked-job');

        $this->actingAs($this->user('admin'))
            ->get(route('admin.employers.index'))
            ->assertOk()
            ->assertSee('Pending review')
            ->assertSee('Company 1');
        $this->assertDatabaseHas('job_listings', ['slug' => 'linked-job']);
    }

    public function test_editor_can_manage_drafts_but_cannot_publish_or_review_applications(): void
    {
        $employer = $this->employer();
        $job = $this->job($employer, 'draft', ['status' => 'draft']);
        $this->actingAs($this->user('editor'))
            ->get(route('admin.jobs.index'))->assertOk()
            ->assertDontSee('Save review');
        $this->patch(route('admin.jobs.application-review', $job), [
            'application_review_status' => 'verified',
            'application_method' => 'external',
        ])->assertForbidden();
        $this->assertDatabaseHas('job_listings', ['id' => $job->id, 'status' => 'draft']);
    }

    public function test_viewer_cannot_edit_articles_or_employers(): void
    {
        $this->actingAs($this->user('viewer'))
            ->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.articles.index'))->assertForbidden();
        $this->get(route('admin.employers.index'))->assertForbidden();
    }

    public function test_admin_article_list_shows_draft_and_scheduled_publication_status(): void
    {
        Article::create([
            'slug' => 'scheduled',
            'title' => 'Scheduled article',
            'description' => 'Description',
            'category' => 'Career',
            'body' => 'Body',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->actingAs($this->user('admin'))
            ->get(route('admin.articles.index'))
            ->assertOk()->assertSee('Scheduled article')
            ->assertSee('Scheduled');
    }
}
