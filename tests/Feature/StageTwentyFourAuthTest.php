<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StageTwentyFourAuthTest extends TestCase {
    use RefreshDatabase;

    public function test_seeker_can_request_password_reset(): void {
        Notification::fake();
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->post(route('seeker.password.email'),['email'=>$user->email])
            ->assertRedirect()->assertSessionHas('status');
        Notification::assertSentTo($user,ResetPassword::class);
    }

    public function test_employer_reset_cannot_send_to_seeker(): void {
        Notification::fake();
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->post(route('employer.password.email'),['email'=>$user->email])
            ->assertRedirect()->assertSessionHas('status');
        Notification::assertNothingSent();
    }

    public function test_verification_link_is_signed_and_account_specific(): void {
        $user=User::factory()->unverified()->create(['role'=>'job_seeker']);
        $url=\Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',now()->addMinutes(60),
            ['id'=>$user->id,'hash'=>sha1($user->email)]
        );
        $this->actingAs($user)->get($url)->assertRedirect(route('seeker.dashboard'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_invalid_verification_link_is_rejected(): void {
        $user=User::factory()->unverified()->create(['role'=>'job_seeker']);
        $this->actingAs($user)->get(route('verification.verify',[
            'id'=>$user->id,'hash'=>sha1($user->email)
        ]))->assertForbidden();
        $this->assertNull($user->fresh()->email_verified_at);
    }
}
