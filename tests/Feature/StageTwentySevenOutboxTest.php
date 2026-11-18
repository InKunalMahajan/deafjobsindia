<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StageTwentySevenOutboxTest extends TestCase {
    use RefreshDatabase;

    public function test_delivery_is_disabled_by_default(): void {
        config(['deafjobsindia.alert_delivery_enabled'=>false]);
        Mail::fake();
        $this->artisan('jobs:deliver-alert-outbox')->assertExitCode(1);
        Mail::assertNothingSent();
    }

    public function test_outbox_dry_run_does_not_modify_rows_or_send_mail(): void {
        Mail::fake();
        $this->artisan('jobs:deliver-alert-outbox',['--dry-run'=>true])->assertSuccessful();
        $this->assertDatabaseCount('job_alert_outbox',0);
        Mail::assertNothingSent();
    }

    public function test_unsubscribed_outbox_is_cancelled_before_delivery(): void {
        config(['deafjobsindia.alert_delivery_enabled'=>true]);
        Mail::fake();
        $user=User::factory()->create(['role'=>'job_seeker','email_verified_at'=>now()]);
        $alert=DB::table('job_alerts')->insertGetId([
            'user_id'=>$user->id,'keywords'=>'Laravel','enabled'=>true,
            'email_opt_in'=>false,'frequency'=>'daily',
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        DB::table('job_alert_outbox')->insert([
            'job_alert_id'=>$alert,'user_id'=>$user->id,
            'dedupe_key'=>hash('sha256','test-outbox-opt-out'),
            'job_ids'=>'[1]','status'=>'pending','created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->artisan('jobs:deliver-alert-outbox')->assertSuccessful();
        $this->assertDatabaseHas('job_alert_outbox',['job_alert_id'=>$alert,'status'=>'cancelled']);
        Mail::assertNothingSent();
    }
}
