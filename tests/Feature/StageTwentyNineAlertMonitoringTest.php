<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StageTwentyNineAlertMonitoringTest extends TestCase {
    use RefreshDatabase;

    public function test_job_seeker_cannot_view_admin_delivery_monitor(): void {
        $user=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($user)->get(route('admin.alert-deliveries.index'))->assertForbidden();
    }

    public function test_admin_can_queue_failed_delivery_without_sending(): void {
        $admin=User::factory()->create(['role'=>'admin']);
        $seeker=User::factory()->create(['role'=>'job_seeker']);
        $alert=DB::table('job_alerts')->insertGetId([
            'user_id'=>$seeker->id,'keywords'=>'Laravel','frequency'=>'daily',
            'enabled'=>true,'email_opt_in'=>true,
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        $delivery=DB::table('job_alert_outbox')->insertGetId([
            'job_alert_id'=>$alert,'user_id'=>$seeker->id,
            'dedupe_key'=>hash('sha256','stage29-test'),'job_ids'=>'[1]',
            'status'=>'failed','attempts'=>2,'created_at'=>now(),'updated_at'=>now(),
        ]);
        $this->actingAs($admin)->post(route('admin.alert-deliveries.retry',$delivery))->assertRedirect();
        $this->assertDatabaseHas('job_alert_outbox',['id'=>$delivery,'status'=>'pending','attempts'=>0]);
        $this->assertDatabaseHas('alert_delivery_audits',['delivery_id'=>$delivery,'actor_id'=>$admin->id,'action'=>'retry_queued']);
    }
}
