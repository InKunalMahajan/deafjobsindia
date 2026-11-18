<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class JobSeekerApplicationHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_application_tracker(): void
    {
        $this->get(route('seeker.applications'))->assertRedirect(route('login'));
    }

    public function test_admin_cannot_access_job_seeker_tracker(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']))
            ->get(route('seeker.applications'))->assertForbidden();
    }

    public function test_seeker_sees_only_own_application_tracker(): void
    {
        $owner=User::factory()->create(['role'=>'job_seeker']);
        $other=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($other)->get(route('seeker.applications'))
            ->assertOk()->assertSee('No applications recorded');
        $this->actingAs($owner)->get(route('seeker.applications'))
            ->assertOk()->assertSee('My application tracker');
    }

    public function test_invalid_status_filter_is_rejected(): void
    {
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($user)->get(route('seeker.applications',['status'=>'not-real']))
            ->assertStatus(422);
    }
}
