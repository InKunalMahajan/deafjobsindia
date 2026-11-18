<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageSevenBEmployerAuditTest extends TestCase
{
    use RefreshDatabase;

    private function employer(string $slug, bool $verified = true): Employer
    {
        return Employer::create([
            'name' => 'Company '.$slug,
            'slug' => $slug,
            'verified' => $verified,
            'contact_email' => $slug.'@example.org',
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
            'summary' => 'Position summary',
            'description' => 'Position description',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $extra));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_directory_count_and_profile_exclude_future_draft_and_expired_jobs(): void
    {
        $employer = $this->employer('reviewed');
        $this->job($employer, 'available');
        $this->job($employer, 'future', ['published_at' => now()->addDay()]);
        $this->job($employer, 'draft', ['status' => 'draft']);
        $this->job($employer, 'expired', ['deadline' => today()->subDay()]);

        $this->get('/employers')->assertOk()
            ->assertSee('1 open vacancy')
            ->assertDontSee('reviewed@example.org');

        $this->get('/employers/reviewed')->assertOk()
            ->assertSee('Position available')
            ->assertDontSee('Position future')
            ->assertDontSee('Position draft')
            ->assertDontSee('Position expired')
            ->assertDontSee('reviewed@example.org');
    }

    public function test_directory_pagination_has_stable_order_and_only_verified_employers(): void
    {
        foreach (range(1, 14) as $i) {
            $this->employer(sprintf('company-%02d', $i));
        }
        $this->employer('hidden', false);

        $this->get('/employers')->assertOk()
            ->assertSee('14 reviewed employers')
            ->assertSee('Company company-01')
            ->assertDontSee('Company company-14')
            ->assertDontSee('Company hidden');

        $this->get('/employers?page=2')->assertOk()
            ->assertSee('Company company-14')
            ->assertDontSee('Company company-01');
    }

    public function test_admin_update_without_verified_field_preserves_review_status(): void
    {
        $employer = $this->employer('reviewed');
        $this->actingAs($this->admin())
            ->put(route('admin.employers.update', $employer), [
                'name' => 'Renamed employer',
                'slug' => 'reviewed',
                'location' => 'Mumbai',
            ])->assertRedirect(route('admin.employers.index'));

        $this->assertDatabaseHas('employers', [
            'id' => $employer->id,
            'verified' => true,
            'name' => 'Renamed employer',
        ]);
    }

    public function test_admin_can_explicitly_unverify_employer_without_deleting_jobs(): void
    {
        $employer = $this->employer('reviewed');
        $this->job($employer, 'kept-job');

        $this->actingAs($this->admin())
            ->put(route('admin.employers.update', $employer), [
                'name' => $employer->name,
                'slug' => $employer->slug,
                'verified' => '0',
            ])->assertRedirect(route('admin.employers.index'));

        $this->get('/employers/reviewed')->assertNotFound();
        $this->assertDatabaseHas('job_listings', ['slug' => 'kept-job', 'employer_id' => $employer->id]);
    }

    public function test_editor_and_viewer_cannot_manage_employers(): void
    {
        $employer = $this->employer('reviewed');
        foreach (['editor', 'viewer'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->put(route('admin.employers.update', $employer), [
                    'name' => 'Unauthorized change',
                    'slug' => 'reviewed',
                    'verified' => '0',
                ])->assertForbidden();
        }
        $this->assertDatabaseHas('employers', ['id' => $employer->id, 'verified' => true]);
    }

    public function test_unverified_employer_cannot_be_found_in_public_directory_or_profile(): void
    {
        $this->employer('private', false);
        $this->get('/employers')->assertOk()->assertDontSee('Company private');
        $this->get('/employers/private')->assertNotFound();
    }
}
