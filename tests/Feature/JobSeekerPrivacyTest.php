<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerPrivacyTest extends TestCase {
    use RefreshDatabase;

    public function test_job_seeker_can_download_own_data_without_password(): void {
        $user=User::factory()->create(['role'=>'job_seeker','skills'=>'Laravel']);
        $response=$this->actingAs($user)->get(route('seeker.privacy.export'));
        $response->assertOk();
        $response->assertHeader('Content-Type','application/json; charset=UTF-8');
        $response->assertDownload('deafjobsindia-my-data.json');
        $response->assertDontSee('password');
    }

    public function test_admin_cannot_export_job_seeker_data(): void {
        $this->actingAs(User::factory()->create(['role'=>'admin']))
            ->get(route('seeker.privacy.export'))->assertForbidden();
    }

    public function test_private_privacy_page_has_noindex(): void {
        $this->actingAs(User::factory()->create(['role'=>'job_seeker']))
            ->get(route('seeker.privacy'))->assertOk()
            ->assertSee('noindex,nofollow');
    }
}
