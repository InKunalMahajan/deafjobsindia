<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageTwelveBReleaseAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_cannot_demote_own_account(): void
    {
        $owner = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($owner)->put(route('admin.users.update', $owner), [
            'name' => $owner->name,
            'email' => $owner->email,
            'role' => 'admin',
        ])->assertSessionHasErrors('role');

        $this->assertSame('super_admin', $owner->fresh()->role);
    }

    public function test_super_admin_can_update_own_profile_without_role_change(): void
    {
        $owner = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($owner)->put(route('admin.users.update', $owner), [
            'name' => 'Updated Owner',
            'email' => $owner->email,
            'role' => 'super_admin',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertSame('Updated Owner', $owner->fresh()->name);
    }

    public function test_non_privileged_user_cannot_update_other_user(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $target = User::factory()->create(['role' => 'viewer']);

        $this->actingAs($editor)->put(route('admin.users.update', $target), [
            'name' => 'Changed Name',
            'email' => $target->email,
            'role' => 'admin',
        ])->assertForbidden();

        $this->assertSame('viewer', $target->fresh()->role);
    }

    public function test_admin_login_route_remains_guest_only(): void
    {
        $owner = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($owner)->get(route('admin.login'))->assertRedirect();
    }
}
