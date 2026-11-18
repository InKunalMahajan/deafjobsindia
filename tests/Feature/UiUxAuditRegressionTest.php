<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UiUxAuditRegressionTest extends TestCase {
    use RefreshDatabase;

    public function test_guest_login_pages_have_accessible_password_actions(): void {
        $this->get(route('seeker.login'))->assertOk()
            ->assertSee('Forgot password?')->assertSee('Continue with Google');
        $this->get(route('employer.login'))->assertOk()
            ->assertSee('Forgot password?')->assertSee('Register your company');
    }

    public function test_admin_delivery_monitor_uses_admin_shell(): void {
        $admin=User::factory()->create(['role'=>'admin']);
        $this->actingAs($admin)->get(route('admin.alert-deliveries.index'))
            ->assertOk()->assertSee('Admin navigation')
            ->assertSee('Alert delivery')->assertSee('Job alert delivery monitoring');
    }

    public function test_job_seeker_alert_page_shows_history_heading(): void {
        $seeker=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($seeker)->get(route('seeker.alerts'))
            ->assertOk()->assertSee('Email delivery history')
            ->assertSee('Save alert');
    }

    public function test_job_seeker_dashboard_has_clear_actions_and_empty_state(): void {
        $seeker=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($seeker)->get(route('seeker.dashboard'))
            ->assertOk()
            ->assertSee('Quick actions')
            ->assertSee('Profile and resume')
            ->assertSee('Submitted applications')
            ->assertSee('Start your job search')
            ->assertSee('Browse available jobs');
    }

    public function test_unsigned_unsubscribe_is_not_accessible(): void {
        $this->get('/alerts/unsubscribe/1/1')->assertForbidden();
    }
}
