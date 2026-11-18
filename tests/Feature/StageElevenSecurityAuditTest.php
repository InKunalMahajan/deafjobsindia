<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageElevenSecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    private function job(): JobListing
    {
        $employer = Employer::create([
            'name' => 'Audit Employer',
            'slug' => 'audit-employer',
            'verified' => true,
        ]);

        return JobListing::create([
            'employer_id' => $employer->id,
            'slug' => 'audit-job',
            'title' => 'Audit job',
            'location' => 'Pune',
            'employment_type' => 'full-time',
            'work_mode' => 'onsite',
            'sector' => 'private',
            'summary' => 'Job summary',
            'description' => 'Safe **Markdown**',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
    }

    public function test_admin_login_is_not_cacheable(): void
    {
        $this->get(route('admin.login'))->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_admin_dashboard_is_not_cacheable(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_guest_cannot_modify_job_review(): void
    {
        $job = $this->job();
        $this->patch(route('admin.jobs.application-review', $job), [
            'application_method' => 'external',
            'application_review_status' => 'verified',
        ])->assertRedirect();

        $this->assertNull($job->fresh()->application_reviewed_at);
    }

    public function test_editor_cannot_modify_job_review(): void
    {
        $job = $this->job();
        $this->actingAs(User::factory()->create(['role' => 'editor']))
            ->patch(route('admin.jobs.application-review', $job), [
                'application_method' => 'external',
                'application_review_status' => 'verified',
            ])->assertForbidden();

        $this->assertNull($job->fresh()->application_reviewed_at);
    }

    public function test_unreviewed_status_clears_previous_reviewer_metadata(): void
    {
        $job = $this->job();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch(route('admin.jobs.application-review', $job), [
            'application_method' => 'external',
            'application_review_status' => 'verified',
        ])->assertRedirect();

        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id,
            'application_reviewed_by' => $admin->id,
        ]);

        $this->patch(route('admin.jobs.application-review', $job), [
            'application_method' => 'external',
            'application_review_status' => 'unreviewed',
        ])->assertRedirect();

        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id,
            'application_review_status' => 'unreviewed',
            'application_reviewed_by' => null,
            'application_reviewed_at' => null,
        ]);
    }

    public function test_invalid_review_status_does_not_change_review_metadata(): void
    {
        $job = $this->job();
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->patch(route('admin.jobs.application-review', $job), [
                'application_method' => 'external',
                'application_review_status' => 'invalid-status',
            ])->assertSessionHasErrors('application_review_status');

        $this->assertNull($job->fresh()->application_reviewed_by);
    }

    public function test_public_markdown_strips_script_html(): void
    {
        Article::create([
            'slug' => 'safe-article',
            'title' => 'Safe article',
            'description' => 'Article description',
            'category' => 'Career',
            'body' => 'Hello **world** <script>alert(1)</script>',
            'status' => 'published',
            'is_demo' => false,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('news.show', 'safe-article'))->assertOk()
            ->assertSee('<strong>world</strong>', false)
            ->assertDontSee('<script>', false);
    }

    public function test_employer_contact_email_is_not_public(): void
    {
        Employer::create([
            'name' => 'Private Employer',
            'slug' => 'private-employer',
            'verified' => true,
            'contact_email' => 'private-contact@example.org',
        ]);

        $this->get(route('employers.show', 'private-employer'))->assertOk()
            ->assertDontSee('private-contact@example.org');
    }
}
