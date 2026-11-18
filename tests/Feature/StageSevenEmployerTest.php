<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageSevenEmployerTest extends TestCase
{
    use RefreshDatabase;

    private function employer(string $slug, bool $verified = true): Employer
    {
        return Employer::create([
            'name' => 'Employer '.$slug,
            'slug' => $slug,
            'verified' => $verified,
            'contact_email' => $slug.'@example.org',
        ]);
    }

    private function job(Employer $employer, string $slug, array $attributes = []): JobListing
    {
        return JobListing::create(array_merge([
            'employer_id' => $employer->id,
            'slug' => $slug,
            'title' => 'Vacancy '.$slug,
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
        ], $attributes));
    }

    public function test_directory_lists_only_verified_employers_and_counts_open_published_jobs(): void
    {
        $public = $this->employer('public');
        $private = $this->employer('private', false);
        $this->job($public, 'open');
        $this->job($public, 'draft', ['status' => 'draft']);
        $this->job($public, 'expired', ['deadline' => today()->subDay()]);
        $this->job($private, 'hidden');

        $this->get('/employers')->assertOk()
            ->assertSee('Employer public')
            ->assertSee('1 open vacancy')
            ->assertDontSee('Employer private')
            ->assertDontSee('private@example.org');
    }

    public function test_unverified_employer_profile_is_not_public(): void
    {
        $this->employer('unreviewed', false);
        $this->get('/employers/unreviewed')->assertNotFound();
    }

    public function test_employer_profile_shows_only_open_published_vacancies(): void
    {
        $employer = $this->employer('reviewed');
        $this->job($employer, 'open-job');
        $this->job($employer, 'draft-job', ['status' => 'draft']);
        $this->job($employer, 'expired-job', ['deadline' => today()->subDay()]);

        $this->get('/employers/reviewed')->assertOk()
            ->assertSee('Vacancy open-job')
            ->assertDontSee('Vacancy draft-job')
            ->assertDontSee('Vacancy expired-job')
            ->assertDontSee('reviewed@example.org');
    }

    public function test_public_job_details_do_not_link_to_unverified_profile(): void
    {
        $employer = $this->employer('unreviewed', false);
        $this->job($employer, 'public-vacancy');

        $this->get('/jobs/public-vacancy')->assertOk()
            ->assertSee('Employer profile not yet reviewed')
            ->assertDontSee('View employer profile')
            ->assertDontSee('unreviewed@example.org');
    }

    public function test_admin_cannot_delete_employer_with_any_related_job(): void
    {
        $employer = $this->employer('with-jobs');
        $this->job($employer, 'old-draft', ['status' => 'draft']);
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->delete(route('admin.employers.destroy', $employer))
            ->assertSessionHasErrors('employer');
        $this->assertDatabaseHas('employers', ['id' => $employer->id]);
    }

    public function test_admin_rejects_duplicate_slug_and_non_https_website(): void
    {
        $this->employer('existing');
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post(route('admin.employers.store'), [
                'name' => 'Duplicate', 'slug' => 'existing',
                'website' => 'http://example.org',
            ])->assertSessionHasErrors(['slug', 'website']);
    }

    public function test_job_detail_displays_active_category_and_experience(): void
    {
        $employer = $this->employer('reviewed');
        $category = JobCategory::create(['name' => 'Information Technology', 'slug' => 'it', 'is_active' => true]);
        $this->job($employer, 'it-role', [
            'job_category_id' => $category->id,
            'experience_min_years' => 1,
            'experience_max_years' => 3,
        ]);

        $this->get('/jobs/it-role')->assertOk()
            ->assertSee('Information Technology')
            ->assertSee('1–3 years');
    }
}
