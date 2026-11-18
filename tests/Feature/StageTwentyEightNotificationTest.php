<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class StageTwentyEightNotificationTest extends TestCase {
    use RefreshDatabase;

    public function test_alert_history_is_private(): void {
        $owner=User::factory()->create(['role'=>'job_seeker']);
        $other=User::factory()->create(['role'=>'job_seeker']);
        $alert=DB::table('job_alerts')->insertGetId([
            'user_id'=>$owner->id,'keywords'=>'PrivateSkill','frequency'=>'daily',
            'enabled'=>true,'email_opt_in'=>true,'created_at'=>now(),'updated_at'=>now(),
        ]);
        DB::table('job_alert_outbox')->insert([
            'job_alert_id'=>$alert,'user_id'=>$owner->id,
            'dedupe_key'=>hash('sha256','private-history'),'job_ids'=>'[1]',
            'status'=>'pending','created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->actingAs($other)->get(route('seeker.alerts'))->assertOk()->assertDontSee('PrivateSkill');
        $this->actingAs($owner)->get(route('seeker.alerts'))->assertOk()->assertSee('PrivateSkill');
    }

    public function test_signed_unsubscribe_disables_only_the_target_alert(): void {
        $owner=User::factory()->create(['role'=>'job_seeker']);
        $first=DB::table('job_alerts')->insertGetId([
            'user_id'=>$owner->id,'keywords'=>'PHP','frequency'=>'daily',
            'enabled'=>true,'email_opt_in'=>true,'created_at'=>now(),'updated_at'=>now(),
        ]);
        $second=DB::table('job_alerts')->insertGetId([
            'user_id'=>$owner->id,'keywords'=>'Laravel','frequency'=>'daily',
            'enabled'=>true,'email_opt_in'=>true,'created_at'=>now(),'updated_at'=>now(),
        ]);
        $url=URL::temporarySignedRoute('seeker.alerts.unsubscribe.apply',now()->addMinutes(10),[
            'alert'=>$first,'user'=>$owner->id,
        ]);
        $this->post($url)->assertRedirect(route('seeker.alerts.unsubscribe.done'));
        $this->assertDatabaseHas('job_alerts',['id'=>$first,'email_opt_in'=>false]);
        $this->assertDatabaseHas('job_alerts',['id'=>$second,'email_opt_in'=>true]);
    }

    public function test_unsigned_unsubscribe_is_rejected(): void {
        $this->post('/alerts/unsubscribe/1/1')->assertForbidden();
    }
}
