<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerAccountTest extends TestCase
{
    use RefreshDatabase;

    private function seeker(): User
    {
        return User::factory()->create(['role'=>'job_seeker']);
    }

    private function job(): JobListing
    {
        $employer=Employer::create(['name'=>'Example','slug'=>'example']);
        return JobListing::create([
            'employer_id'=>$employer->id,'slug'=>'example-job','title'=>'Example job',
            'location'=>'Pune','employment_type'=>'full-time','work_mode'=>'remote',
            'sector'=>'private','summary'=>'Summary','description'=>'Description',
            'source_url'=>'https://example.org/source','apply_url'=>'https://example.org/apply',
            'status'=>'published','published_at'=>now()->subDay(),
        ]);
    }

    public function test_registration_creates_only_job_seeker(): void
    {
        $this->post(route('seeker.register.store'),[
            'name'=>'New Seeker','email'=>'seeker@example.org',
            'password'=>'secure-password-123','password_confirmation'=>'secure-password-123',
        ])->assertRedirect(route('seeker.dashboard'));
        $this->assertDatabaseHas('users',['email'=>'seeker@example.org','role'=>'job_seeker']);
    }

    public function test_job_seeker_login_rejects_admin(): void
    {
        $admin=User::factory()->create(['role'=>'admin','password'=>'secure-password-123']);
        $this->post(route('seeker.login.store'),[
            'email'=>$admin->email,'password'=>'secure-password-123',
        ])->assertSessionHasErrors('email');
    }

    public function test_admin_cannot_open_seeker_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']))
            ->get(route('seeker.dashboard'))->assertForbidden();
    }

    public function test_seeker_cannot_open_admin_dashboard(): void
    {
        $this->actingAs($this->seeker())->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_seeker_can_save_job_and_track_application(): void
    {
        $job=$this->job();
        $seeker=$this->seeker();
        $this->actingAs($seeker)->post(route('seeker.jobs.save',$job))->assertRedirect();
        $this->assertDatabaseHas('saved_jobs',['user_id'=>$seeker->id,'job_listing_id'=>$job->id]);
        $this->post(route('seeker.jobs.track',$job),[
            'status'=>'applied','notes'=>'Applied through employer website',
        ])->assertRedirect();
        $this->assertDatabaseHas('job_application_trackers',[
            'user_id'=>$seeker->id,'job_listing_id'=>$job->id,'status'=>'applied',
        ]);
    }

    public function test_seeker_cannot_modify_other_seeker_saved_jobs(): void
    {
        $job=$this->job();
        $owner=$this->seeker();
        $other=$this->seeker();
        $this->actingAs($owner)->post(route('seeker.jobs.save',$job));
        $this->actingAs($other)->delete(route('seeker.jobs.unsave',$job));
        $this->assertDatabaseHas('saved_jobs',['user_id'=>$owner->id,'job_listing_id'=>$job->id]);
    }

    public function test_seeker_profile_update_does_not_change_role(): void
    {
        $seeker=$this->seeker();
        $this->actingAs($seeker)->patch(route('seeker.profile.update'),[
            'name'=>'Updated','location'=>'Pune','bio'=>'Looking for work',
            'role'=>'super_admin',
        ])->assertRedirect();
        $this->assertDatabaseHas('users',['id'=>$seeker->id,'name'=>'Updated','role'=>'job_seeker']);
    }
}
