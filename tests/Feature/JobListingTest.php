<?php

namespace Tests\Feature;

use App\Livewire\JobSearch;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class JobListingTest extends TestCase {
    use RefreshDatabase;

    private function job(array $attributes = []): JobListing {
        $employer = Employer::firstOrCreate(['slug'=>'sample-employer'],['name'=>'Sample Employer']);
        return JobListing::create(array_merge([
            'employer_id'=>$employer->id,
            'slug'=>'sample-job-'.JobListing::count(),
            'title'=>'Accessible Software Developer',
            'location'=>'Pune',
            'state'=>'Maharashtra',
            'employment_type'=>'full-time',
            'work_mode'=>'remote',
            'sector'=>'private',
            'is_deaf_friendly'=>true,
            'is_pwd_eligible'=>true,
            'summary'=>'Software engineering opportunity',
            'description'=>'Build accessible software.',
            'requirements'=>'PHP experience',
            'source_url'=>'https://example.org/notice',
            'apply_url'=>'https://example.org/apply',
            'deadline'=>today()->addDays(10),
            'status'=>'published',
            'published_at'=>now()->subDay(),
        ],$attributes));
    }

    public function test_jobs_page_renders_and_lists_published_open_jobs(): void {
        $this->job();
        $this->get('/jobs')->assertOk()->assertSee('Accessible Software Developer');
    }

    public function test_detail_page_shows_employer_and_official_application(): void {
        $job=$this->job();
        $this->get(route('jobs.show',$job->slug))->assertOk()
            ->assertSee('Sample Employer')->assertSee('Apply on official site')
            ->assertSee('Read original recruitment source');
    }

    public function test_draft_and_future_jobs_are_not_public(): void {
        $this->job(['slug'=>'draft-job','status'=>'draft']);
        $this->job(['slug'=>'future-job','published_at'=>now()->addDay()]);
        $this->get('/jobs/draft-job')->assertNotFound();
        $this->get('/jobs/future-job')->assertNotFound();
        $this->get('/jobs')->assertDontSee('draft-job');
    }

    public function test_expired_jobs_are_hidden_by_default_and_cannot_be_applied_to(): void {
        $job=$this->job(['deadline'=>today()->subDay()]);
        $this->get('/jobs')->assertDontSee('Accessible Software Developer');
        $this->get(route('jobs.show',$job->slug))->assertOk()
            ->assertSee('Application closed')->assertDontSee('Apply on official site');
    }

    public function test_filters_and_reset_work(): void {
        $this->job();
        $this->job(['slug'=>'other-job','title'=>'Office Assistant','location'=>'Delhi',
            'sector'=>'government','work_mode'=>'onsite','is_deaf_friendly'=>false]);
        Livewire::test(JobSearch::class)->set('location','Pune')
            ->assertSee('Accessible Software Developer')->assertDontSee('Office Assistant')
            ->set('sector','government')->assertDontSee('Accessible Software Developer')
            ->call('clearFilters')->assertSee('Office Assistant');
    }

    public function test_search_does_not_include_unpublished_jobs(): void {
        $this->job(['slug'=>'published-job']);
        $this->job(['slug'=>'draft-secret','title'=>'Secret Draft Job','status'=>'draft']);
        Livewire::test(JobSearch::class)->set('query','Secret Draft Job')
            ->assertDontSee('Secret Draft Job');
    }
}
