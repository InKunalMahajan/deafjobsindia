<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageNineBAdminAuditTest extends TestCase
{
    use RefreshDatabase;

    private function employer(): Employer
    {
        return Employer::create(['name' => 'Audit Employer', 'slug' => 'audit-employer', 'verified' => false]);
    }

    private function job(Employer $employer, string $slug, array $attributes = []): JobListing
    {
        return JobListing::create(array_merge([
            'employer_id' => $employer->id,
            'slug' => $slug,
            'title' => 'Audit '.$slug,
            'location' => 'Pune',
            'employment_type' => 'full-time',
            'work_mode' => 'remote',
            'sector' => 'private',
            'summary' => 'Summary',
            'description' => 'Description',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_dashboard_counts_legacy_null_and_unreviewed_application_guidance(): void
    {
        $employer = $this->employer();
        $this->job($employer, 'legacy', ['application_review_status' => null]);
        $this->job($employer, 'unreviewed', ['application_review_status' => 'unreviewed']);
        $this->job($employer, 'verified', ['application_review_status' => 'verified']);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Unreviewed application guidance')
            ->assertViewHas('jobsUnreviewed', 2)
            ->assertViewHas('pendingEmployers', 1)
            ->assertViewHas('openJobs', 3);
    }

    public function test_application_guidance_review_updates_status_and_reviewer(): void
    {
        $job = $this->job($this->employer(), 'needs-review');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.jobs.application-review', $job), [
                'application_method' => 'external',
                'application_review_status' => 'verified',
            ])->assertRedirect();

        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id,
            'application_review_status' => 'verified',
            'application_reviewed_by' => $admin->id,
        ]);
        $this->assertNotNull($job->fresh()->application_reviewed_at);
    }

    public function test_editor_cannot_change_application_guidance_review(): void
    {
        $job = $this->job($this->employer(), 'protected');
        $this->actingAs(User::factory()->create(['role' => 'editor']))
            ->patch(route('admin.jobs.application-review', $job), [
                'application_method' => 'external',
                'application_review_status' => 'verified',
            ])->assertForbidden();

        $this->assertNotEquals('verified', $job->fresh()->application_review_status);
    }

    public function test_invalid_review_status_is_rejected_without_mutating_job(): void
    {
        $job = $this->job($this->employer(), 'invalid');
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->patch(route('admin.jobs.application-review', $job), [
                'application_method' => 'external',
                'application_review_status' => 'approved_without_review',
            ])->assertSessionHasErrors('application_review_status');

        $this->assertNull($job->fresh()->application_reviewed_at);
    }

    public function test_dashboard_excludes_future_publications_from_public_counts(): void
    {
        $employer = $this->employer();
        $this->job($employer, 'scheduled', ['published_at' => now()->addDay()]);
        Article::create([
            'slug' => 'scheduled-article',
            'title' => 'Scheduled article',
            'description' => 'Description',
            'category' => 'Career',
            'body' => 'Body',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('published', 0)
            ->assertViewHas('publishedArticles', 0)
            ->assertViewHas('openJobs', 0);
    }

    public function test_employer_verification_does_not_remove_related_jobs(): void
    {
        $employer = $this->employer();
        $job = $this->job($employer, 'linked');
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->put(route('admin.employers.update', $employer), [
                'name' => $employer->name,
                'slug' => $employer->slug,
                'verified' => '1',
            ])->assertRedirect(route('admin.employers.index'));

        $this->assertDatabaseHas('employers', ['id' => $employer->id, 'verified' => true]);
        $this->assertDatabaseHas('job_listings', ['id' => $job->id, 'employer_id' => $employer->id]);
    }
}
