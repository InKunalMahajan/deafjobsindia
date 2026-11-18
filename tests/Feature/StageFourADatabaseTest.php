<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\JobCategory;
use App\Models\JobListing;
use App\Models\JobReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class StageFourADatabaseTest extends TestCase {
    use RefreshDatabase;

    public function test_category_and_job_report_relationships_and_nullable_category(): void {
        $category = JobCategory::create(['name' => 'IT', 'slug' => 'it']);
        $employer = Employer::create(['name' => 'Example Employer', 'slug' => 'example-employer']);
        $job = JobListing::create([
            'employer_id' => $employer->id, 'slug' => 'stage-04a-job',
            'title' => 'Developer', 'location' => 'Pune',
            'employment_type' => 'full-time', 'work_mode' => 'remote',
            'sector' => 'private', 'summary' => 'Example vacancy',
            'description' => 'Example description',
            'source_url' => 'https://example.org/source',
            'apply_url' => 'https://example.org/apply',
            'status' => 'draft',
        ]);
        $job->job_category_id = $category->id;
        $job->is_featured = true;
        $job->save();
        $report = JobReport::create([
            'job_listing_id' => $job->id, 'reason' => 'incorrect_details',
        ]);
        $this->assertSame($category->id, $job->fresh()->job_category_id);
        $this->assertTrue((bool) $job->fresh()->is_featured);
        $this->assertSame($job->id, $report->jobListing->id);
        $this->assertSame(1, $category->jobListings()->count());
        $this->assertSame(1, $job->reports()->count());
    }

    public function test_existing_role_permissions_are_enforced(): void {
        foreach (['viewer' => false, 'editor' => true, 'admin' => true, 'super_admin' => true] as $role => $allowed) {
            $user = User::factory()->create();
            $user->role = $role;
            $user->save();
            $this->assertSame($allowed, Gate::forUser($user)->allows('jobs.edit'));
        }
    }
}
