<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class JobAlertEmailTest extends TestCase {
    use RefreshDatabase;

    public function test_alert_email_is_off_by_default(): void {
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($user)->post(route('seeker.alerts.store'),[
            'keywords'=>'PHP','frequency'=>'daily',
        ])->assertRedirect();
        $this->assertDatabaseHas('job_alerts',['user_id'=>$user->id,'email_opt_in'=>false]);
    }

    public function test_job_seeker_can_opt_in_and_unsubscribe(): void {
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($user)->post(route('seeker.alerts.store'),[
            'keywords'=>'PHP','frequency'=>'weekly','email_opt_in'=>'1',
        ])->assertRedirect();
        $alert=DB::table('job_alerts')->where('user_id',$user->id)->first();
        $this->assertTrue((bool)$alert->email_opt_in);
        $this->patch(route('seeker.alerts.update',$alert->id),[
            'frequency'=>'daily',
        ])->assertRedirect();
        $this->assertDatabaseHas('job_alerts',['id'=>$alert->id,'email_opt_in'=>false,'frequency'=>'daily']);
    }

    public function test_other_seeker_cannot_modify_alert(): void {
        $owner=User::factory()->create(['role'=>'job_seeker']);
        $other=User::factory()->create(['role'=>'job_seeker']);
        $id=DB::table('job_alerts')->insertGetId([
            'user_id'=>$owner->id,'keywords'=>'Laravel','frequency'=>'daily',
            'enabled'=>true,'email_opt_in'=>false,'created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->actingAs($other)->patch(route('seeker.alerts.update',$id),[
            'frequency'=>'weekly','email_opt_in'=>'1',
        ])->assertRedirect();
        $this->assertDatabaseHas('job_alerts',['id'=>$id,'email_opt_in'=>false,'frequency'=>'daily']);
    }

    public function test_unsubscribed_alert_is_excluded_from_digest(): void {
        Mail::fake();
        $user=User::factory()->create(['role'=>'job_seeker','email_verified_at'=>now()]);
        DB::table('job_alerts')->insert([
            'user_id'=>$user->id,'keywords'=>'PHP','frequency'=>'daily',
            'enabled'=>true,'email_opt_in'=>false,
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->artisan('jobs:send-alerts')->assertSuccessful();
        Mail::assertNothingSent();
    }

    public function test_dry_run_sends_no_emails(): void {
        Mail::fake();
        $this->artisan('jobs:send-alerts',['--dry-run'=>true])->assertSuccessful();
        Mail::assertNothingSent();
    }
}
