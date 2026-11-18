<?php

namespace Tests\Feature;

use App\Livewire\JobSearch;
use App\Models\Employer;
use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StageSixDiscoveryTest extends TestCase {
    use RefreshDatabase;

    private function job(string $slug, array $attributes = []): JobListing {
        $employer = Employer::firstOrCreate(['slug' => 'stage-six-employer'], ['name' => 'Stage Six Employer']);
        return JobListing::create(array_merge([
            'employer_id' => $employer->id, 'slug' => $slug,
            'title' => $slug, 'location' => 'Pune',
            'employment_type' => 'full-time', 'work_mode' => 'remote',
            'sector' => 'private', 'summary' => 'Job opportunity',
            'description' => 'Job description',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'published', 'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_category_and_featured_filters_exclude_drafts(): void {
        $category = JobCategory::create(['name' => 'IT', 'slug' => 'it']);
        $this->job('featured-it', ['job_category_id' => $category->id, 'is_featured' => true]);
        $this->job('ordinary-it', ['job_category_id' => $category->id]);
        $this->job('secret-draft', ['job_category_id' => $category->id, 'is_featured' => true, 'status' => 'draft']);
        Livewire::test(JobSearch::class)->set('category', 'it')->set('featuredOnly', true)
            ->assertSee('featured-it')->assertDontSee('ordinary-it')->assertDontSee('secret-draft');
    }

    public function test_salary_filter_only_uses_structured_values(): void {
        $this->job('high-salary', ['salary_min_annual' => 700000, 'salary_max_annual' => 900000]);
        $this->job('low-salary', ['salary_min_annual' => 100000, 'salary_max_annual' => 250000]);
        $this->job('unknown-salary', ['salary_text' => 'Negotiable']);
        Livewire::test(JobSearch::class)->set('salary', '600000')
            ->assertSee('high-salary')->assertDontSee('low-salary')->assertDontSee('unknown-salary');
    }

    public function test_experience_filter_respects_minimum_and_maximum(): void {
        $this->job('junior-job', ['experience_min_years' => 0, 'experience_max_years' => 2]);
        $this->job('senior-job', ['experience_min_years' => 5, 'experience_max_years' => 10]);
        Livewire::test(JobSearch::class)->set('experience', '1')
            ->assertSee('junior-job')->assertDontSee('senior-job');
    }

    public function test_homepage_only_features_published_open_jobs(): void {
        $this->job('featured-open', ['is_featured' => true]);
        $this->job('featured-draft', ['is_featured' => true, 'status' => 'draft']);
        $this->job('featured-expired', ['is_featured' => true, 'deadline' => today()->subDay()]);
        $this->get('/')->assertOk()->assertSee('Featured jobs')
            ->assertSee('featured-open')->assertDontSee('featured-draft')
            ->assertDontSee('featured-expired');
    }

    public function test_reset_clears_new_filters(): void {
        $this->job('ordinary-job');
        Livewire::test(JobSearch::class)->set('featuredOnly', true)
            ->assertDontSee('ordinary-job')->call('clearFilters')->assertSee('ordinary-job');
    }

    public function test_pagination_keeps_results_separate(): void {
        for ($i = 0; $i < 13; $i++) $this->job('listing-'.$i);
        Livewire::test(JobSearch::class)->assertSee('13 jobs found')->assertSee('listing-12');
    }
}
