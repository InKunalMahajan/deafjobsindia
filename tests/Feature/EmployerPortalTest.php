<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EmployerPortalTest extends TestCase {
    use RefreshDatabase;

    public function test_employer_registration_is_unverified_and_separate(): void {
        $this->post(route('employer.register.store'),[
            'name'=>'Employer Contact','email'=>'recruiter@example.org',
            'password'=>'strong-password-123','password_confirmation'=>'strong-password-123',
            'company_name'=>'Example Recruitment','company_location'=>'Pune',
        ])->assertRedirect(route('employer.dashboard'));
        $this->assertDatabaseHas('users',['email'=>'recruiter@example.org','role'=>'employer']);
        $this->assertDatabaseHas('employers',['name'=>'Example Recruitment','verified'=>false]);
        $user=User::where('email','recruiter@example.org')->firstOrFail();
        $this->assertDatabaseHas('employer_memberships',['user_id'=>$user->id]);
        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('seeker.dashboard'))->assertForbidden();
    }

    public function test_job_seeker_cannot_access_employer_dashboard(): void {
        $this->actingAs(User::factory()->create(['role'=>'job_seeker']))
            ->get(route('employer.dashboard'))->assertForbidden();
    }

    public function test_employer_submission_is_draft_and_not_public(): void {
        $this->post(route('employer.register.store'),[
            'name'=>'Employer Contact','email'=>'recruiter@example.org',
            'password'=>'strong-password-123','password_confirmation'=>'strong-password-123',
            'company_name'=>'Example Recruitment','company_location'=>'Pune',
        ])->assertRedirect();
        $this->post(route('employer.jobs.store'),[
            'title'=>'Junior Developer','location'=>'Pune','employment_type'=>'full-time',
            'work_mode'=>'remote','summary'=>'An entry level role',
            'description'=>'Job details','apply_url'=>'https://example.org/apply',
        ])->assertRedirect(route('employer.dashboard'));
        $this->assertDatabaseHas('job_listings',['title'=>'Junior Developer','status'=>'draft','published_at'=>null]);
    }

    public function test_admin_cannot_access_employer_dashboard(): void {
        $this->actingAs(User::factory()->create(['role'=>'admin']))
            ->get(route('employer.dashboard'))->assertForbidden();
    }
}
