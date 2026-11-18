<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class StageTwentyFiveAuthHardeningTest extends TestCase {
    use RefreshDatabase;

    public function test_google_only_account_cannot_request_password_reset(): void {
        Notification::fake();
        $user=User::factory()->create(['role'=>'job_seeker','google_id'=>'google-123']);
        $this->post(route('seeker.password.email'),['email'=>$user->email])
            ->assertRedirect()->assertSessionHas('status');
        Notification::assertNothingSent();
    }

    public function test_password_reset_revokes_existing_database_sessions(): void {
        config(['session.driver'=>'database']);
        $user=User::factory()->create(['role'=>'job_seeker','google_id'=>null]);
        DB::table('sessions')->insert([
            'id'=>'existing-session','user_id'=>$user->id,
            'ip_address'=>'127.0.0.1','user_agent'=>'Test',
            'payload'=>'','last_activity'=>time(),
        ]);
        $token=Password::createToken($user);
        $this->post(route('seeker.password.update'),[
            'token'=>$token,'email'=>$user->email,
            'password'=>'SecurePassword123!','password_confirmation'=>'SecurePassword123!',
        ])->assertRedirect(route('seeker.login'));
        $this->assertDatabaseMissing('sessions',['id'=>'existing-session']);
    }

    public function test_mail_readiness_fails_for_log_mailer(): void {
        config(['mail.default'=>'log','mail.from.address'=>'hello@example.com','app.url'=>'http://localhost']);
        $this->artisan('mail:readiness')->assertExitCode(1);
    }
}
