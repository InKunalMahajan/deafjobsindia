<?php

namespace Tests\Feature;

use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobSeekerDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    private function seeker(): User
    {
        return User::factory()->create(['role'=>'job_seeker']);
    }

    public function test_seeker_can_update_preferences(): void
    {
        $user=$this->seeker();
        $this->actingAs($user)->patch(route('seeker.profile.update'),[
            'name'=>'Example Seeker','skills'=>'PHP, Laravel',
            'preferred_location'=>'Pune','preferred_work_mode'=>'remote',
            'requires_captions'=>'1','prefers_isl'=>'1',
        ])->assertRedirect();
        $this->assertDatabaseHas('users',['id'=>$user->id,'skills'=>'PHP, Laravel','prefers_isl'=>true]);
    }

    public function test_resume_is_private_to_owner(): void
    {
        Storage::fake('local');
        $owner=$this->seeker();
        $other=$this->seeker();
        $this->actingAs($owner)->post(route('seeker.resume.store'),[
            'resume'=>UploadedFile::fake()->create('resume.pdf',100,'application/pdf'),
        ])->assertRedirect();
        $path=$owner->fresh()->resume_path;
        $this->assertNotNull($path);
        Storage::disk('local')->assertExists($path);
        $this->get(route('seeker.resume.download'))->assertOk();
        $this->actingAs($other)->get(route('seeker.resume.download'))->assertNotFound();
        $this->actingAs($owner)->delete(route('seeker.resume.destroy'))->assertRedirect();
        Storage::disk('local')->assertMissing($path);
    }

    public function test_alerts_are_owned_by_seeker(): void
    {
        $owner=$this->seeker();
        $other=$this->seeker();
        $this->actingAs($owner)->post(route('seeker.alerts.store'),[
            'keywords'=>'Laravel','location'=>'Pune',
        ])->assertRedirect();
        $alert=\Illuminate\Support\Facades\DB::table('job_alerts')->where('user_id',$owner->id)->first();
        $this->actingAs($other)->delete(route('seeker.alerts.delete',$alert->id))->assertRedirect();
        $this->assertDatabaseHas('job_alerts',['id'=>$alert->id,'user_id'=>$owner->id]);
    }

    public function test_admin_cannot_upload_resume(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']))
            ->post(route('seeker.resume.store'),[])->assertForbidden();
    }
}
