<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StageTwentyThreeIntegrationTest extends TestCase {
    use RefreshDatabase;

    private function internalJob(): JobListing {
        $employer=Employer::create(['name'=>'Integration Employer','slug'=>'integration-employer','verified'=>true]);
        return JobListing::create([
            'employer_id'=>$employer->id,'slug'=>'integration-role','title'=>'Integration Role',
            'location'=>'Pune','employment_type'=>'full-time','work_mode'=>'remote',
            'sector'=>'private','summary'=>'A role','description'=>'Role description',
            'source_url'=>'https://example.org/source','apply_url'=>'https://example.org/apply',
            'status'=>'published','published_at'=>now()->subDay(),'application_method'=>'internal',
        ]);
    }

    public function test_unverified_employer_cannot_receive_new_internal_applications(): void {
        $job=$this->internalJob();
        $job->employer->update(['verified'=>false]);
        $seeker=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($seeker)->post(route('seeker.apply',$job),[
            'consent'=>'1',
        ])->assertNotFound();
        $this->assertDatabaseCount('recruitment_applications',0);
    }

    public function test_interview_status_requires_future_datetime(): void {
        $job=$this->internalJob();
        $employer=User::factory()->create(['role'=>'employer']);
        $candidate=User::factory()->create(['role'=>'job_seeker']);
        DB::table('employer_memberships')->insert([
            'user_id'=>$employer->id,'employer_id'=>$job->employer_id,
            'role'=>'owner','created_at'=>now(),'updated_at'=>now(),
        ]);
        $id=DB::table('recruitment_applications')->insertGetId([
            'user_id'=>$candidate->id,'job_listing_id'=>$job->id,'status'=>'submitted',
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->actingAs($employer)->patch(route('employer.applications.update',[$job,$id]),[
            'status'=>'interview',
        ])->assertSessionHasErrors('interview_at');
        $this->assertDatabaseHas('recruitment_applications',['id'=>$id,'status'=>'submitted']);
    }
}
