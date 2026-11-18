<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageTwentyTwoSecurityTest extends TestCase {
    use RefreshDatabase;

    public function test_job_seeker_dashboard_is_not_cached(): void {
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($user)->get(route('seeker.dashboard'))
            ->assertOk()->assertHeader('Cache-Control','no-store, private')
            ->assertHeader('X-Frame-Options','DENY')
            ->assertHeader('X-Content-Type-Options','nosniff');
    }

    public function test_employer_login_page_is_not_cached(): void {
        $this->get(route('employer.login'))
            ->assertOk()->assertHeader('Cache-Control','no-store, private');
    }

    public function test_employer_cannot_access_seeker_privacy_export(): void {
        $this->actingAs(User::factory()->create(['role'=>'employer']))
            ->get(route('seeker.privacy.export'))->assertForbidden();
    }

    public function test_seeker_cannot_access_employer_dashboard(): void {
        $this->actingAs(User::factory()->create(['role'=>'job_seeker']))
            ->get(route('employer.dashboard'))->assertForbidden();
    }
}
