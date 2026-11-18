<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RecruitmentWorkflowTest extends TestCase {
    use RefreshDatabase;

    private function job(): JobListing {
        $employer=Employer::create(['name'=>'Recruiter Example','slug'=>'recruiter-example','verified'=>true]);
        return JobListing::create([
            'employer_id'=>$employer->id,'slug'=>'developer-test','title'=>'Developer',
            'location'=>'Pune','employment_type'=>'full-time','work_mode'=>'remote',
            'sector'=>'private','summary'=>'Role summary','description'=>'Role details',
            'source_url'=>'https://example.org/source','apply_url'=>'https://example.org/apply',
            'status'=>'published','published_at'=>now()->subMinute(),'application_method'=>'internal',
        ]);
    }

    public function test_seeker_can_apply_once_with_consent(): void {
        $job=$this->job();
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($user)->post(route('seeker.apply',$job),[
            'cover_note'=>'I would like to apply.','consent'=>'1',
        ])->assertRedirect(route('seeker.submitted'));
        $this->assertDatabaseHas('recruitment_applications',[
            'user_id'=>$user->id,'job_listing_id'=>$job->id,'status'=>'submitted',
        ]);
        $this->post(route('seeker.apply',$job),['consent'=>'1'])->assertSessionHasErrors('application');
        $this->assertSame(1,DB::table('recruitment_applications')->count());
    }

    public function test_consent_is_required(): void {
        $job=$this->job();
        $this->actingAs(User::factory()->create(['role'=>'job_seeker']))
            ->post(route('seeker.apply',$job),[])->assertSessionHasErrors('consent');
        $this->assertDatabaseCount('recruitment_applications',0);
    }

    public function test_employer_cannot_read_another_company_applications(): void {
        $job=$this->job();
        $other=Employer::create(['name'=>'Other','slug'=>'other']);
        $user=User::factory()->create(['role'=>'employer']);
        DB::table('employer_memberships')->insert([
            'user_id'=>$user->id,'employer_id'=>$other->id,
            'role'=>'owner','created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->actingAs($user)->get(route('employer.applications',$job))->assertForbidden();
    }

    public function test_external_jobs_cannot_receive_internal_applications(): void {
        $job=$this->job();
        $job->update(['application_method'=>'external']);
        $this->actingAs(User::factory()->create(['role'=>'job_seeker']))
            ->post(route('seeker.apply',$job),['consent'=>'1'])->assertNotFound();
    }
}
