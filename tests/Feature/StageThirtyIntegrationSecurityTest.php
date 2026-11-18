<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class StageThirtyIntegrationSecurityTest extends TestCase {
    use RefreshDatabase;

    private function alert(User $user,bool $optIn=true): int {
        return DB::table('job_alerts')->insertGetId([
            'user_id'=>$user->id,'keywords'=>'PHP','frequency'=>'daily',
            'enabled'=>true,'email_opt_in'=>$optIn,
            'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    private function delivery(int $alert,int $user,string $status): int {
        return DB::table('job_alert_outbox')->insertGetId([
            'job_alert_id'=>$alert,'user_id'=>$user,
            'dedupe_key'=>hash('sha256',uniqid('stage30',true)),
            'job_ids'=>'[1]','status'=>$status,
            'attempts'=>5,'created_at'=>now(),'updated_at'=>now(),
        ]);
    }

    public function test_unsubscribe_cancels_exhausted_delivery(): void {
        $seeker=User::factory()->create(['role'=>'job_seeker']);
        $alert=$this->alert($seeker);
        $id=$this->delivery($alert,$seeker->id,'exhausted');
        $url=URL::temporarySignedRoute('seeker.alerts.unsubscribe.apply',now()->addMinutes(10),[
            'alert'=>$alert,'user'=>$seeker->id,
        ]);
        $this->post($url)->assertRedirect(route('seeker.alerts.unsubscribe.done'));
        $this->assertDatabaseHas('job_alert_outbox',['id'=>$id,'status'=>'cancelled']);
        $this->assertDatabaseHas('job_alerts',['id'=>$alert,'email_opt_in'=>false]);
    }

    public function test_admin_cannot_retry_unverified_recipient(): void {
        $admin=User::factory()->create(['role'=>'admin']);
        $seeker=User::factory()->create(['role'=>'job_seeker','email_verified_at'=>null]);
        $alert=$this->alert($seeker);
        $id=$this->delivery($alert,$seeker->id,'failed');
        $this->actingAs($admin)->post(route('admin.alert-deliveries.retry',$id))->assertNotFound();
        $this->assertDatabaseHas('job_alert_outbox',['id'=>$id,'status'=>'failed']);
    }

    public function test_seeker_cannot_retry_delivery(): void {
        $seeker=User::factory()->create(['role'=>'job_seeker']);
        $this->actingAs($seeker)->post(route('admin.alert-deliveries.retry',1))->assertForbidden();
    }

    public function test_delivery_command_stays_disabled_by_default(): void {
        config(['deafjobsindia.alert_delivery_enabled'=>false]);
        $this->artisan('jobs:deliver-alert-outbox')->assertExitCode(1);
    }
}
