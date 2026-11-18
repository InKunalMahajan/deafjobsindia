<?php

namespace Tests\Feature;

use App\Livewire\JobSearch;
use App\Models\Employer;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StageSixBQualityTest extends TestCase {
    use RefreshDatabase;

    private function jobData(Employer $employer, array $extra = []): array {
        return array_merge([
            'employer_id' => $employer->id,
            'slug' => 'quality-job',
            'title' => 'Quality job',
            'location' => 'Pune',
            'employment_type' => 'full-time',
            'work_mode' => 'remote',
            'sector' => 'private',
            'summary' => 'Quality assurance',
            'description' => 'Detailed job description',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'draft',
        ], $extra);
    }

    private function user(string $role): User {
        $user = User::factory()->create();
        $user->role = $role;
        $user->save();
        return $user;
    }

    public function test_admin_edit_form_receives_categories(): void {
        $employer = Employer::create(['name' => 'Quality Employer', 'slug' => 'quality-employer']);
        JobCategory::create(['name' => 'Technology', 'slug' => 'technology']);
        $job = JobListing::create($this->jobData($employer));
        $this->actingAs($this->user('admin'))
            ->get(route('admin.jobs.edit', $job))
            ->assertOk()->assertSee('Technology');
    }

    public function test_editor_cannot_set_featured_flag(): void {
        $employer = Employer::create(['name' => 'Quality Employer', 'slug' => 'quality-employer']);
        $this->actingAs($this->user('editor'))
            ->post(route('admin.jobs.store'), $this->jobData($employer, ['is_featured' => 1]))
            ->assertForbidden();
        $this->assertDatabaseMissing('job_listings', ['slug' => 'quality-job']);
    }

    public function test_editor_can_update_draft_without_clearing_featured_flag(): void {
        $employer = Employer::create(['name' => 'Quality Employer', 'slug' => 'quality-employer']);
        $job = JobListing::create($this->jobData($employer, ['is_featured' => true]));
        $this->actingAs($this->user('editor'))
            ->put(route('admin.jobs.update', $job), $this->jobData($employer, ['title' => 'Updated title']))
            ->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id, 'is_featured' => true, 'title' => 'Updated title',
        ]);
    }

    public function test_salary_and_experience_ranges_must_be_complete_and_ordered(): void {
        $employer = Employer::create(['name' => 'Quality Employer', 'slug' => 'quality-employer']);
        $this->actingAs($this->user('admin'))
            ->post(route('admin.jobs.store'), $this->jobData($employer, [
                'salary_min_annual' => 500000, 'salary_max_annual' => 300000,
                'experience_min_years' => 5, 'experience_max_years' => 2,
            ]))->assertSessionHasErrors(['salary_max_annual','experience_max_years']);
        $this->post(route('admin.jobs.store'), $this->jobData($employer, [
            'salary_min_annual' => 500000, 'experience_max_years' => 3,
        ]))->assertSessionHasErrors(['salary_max_annual','experience_min_years']);
    }

    public function test_inactive_categories_are_not_searchable(): void {
        $category = JobCategory::create(['name' => 'Inactive', 'slug' => 'inactive', 'is_active' => false]);
        $employer = Employer::create(['name' => 'Quality Employer', 'slug' => 'quality-employer']);
        JobListing::create($this->jobData($employer, [
            'job_category_id' => $category->id, 'status' => 'published',
            'published_at' => now()->subDay(),
        ]));
        Livewire::test(JobSearch::class)->set('category', 'inactive')
            ->assertDontSee('Quality job');
    }

    public function test_filters_reset_pagination(): void {
        $employer = Employer::create(['name' => 'Quality Employer', 'slug' => 'quality-employer']);
        foreach (range(1, 14) as $i) {
            JobListing::create($this->jobData($employer, [
                'slug' => 'quality-'.$i, 'title' => 'Quality '.$i,
                'status' => 'published', 'published_at' => now()->subDay(),
            ]));
        }
        Livewire::test(JobSearch::class)->call('nextPage')
            ->assertSet('paginators.page', 2)
            ->set('location', 'Mumbai')
            ->assertSet('paginators.page', 1)
            ->assertSee('0 jobs found');
    }
}
