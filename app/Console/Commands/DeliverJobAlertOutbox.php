<?php

namespace App\Console\Commands;

use App\Mail\JobAlertDigest;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DeliverJobAlertOutbox extends Command {
    protected $signature='jobs:deliver-alert-outbox {--dry-run : Preview pending digests without sending} {--limit=50 : Maximum outbox rows to process}';
    protected $description='Deliver staged job alert digests with bounded retry attempts';

    public function handle(): int {
        $dryRun=(bool)$this->option('dry-run');
        $limit=max(1,min(500,(int)$this->option('limit')));
        $rows=DB::table('job_alert_outbox')->whereIn('status',['pending','failed'])
            ->where('attempts','<',5)
            ->where(fn($q)=>$q->whereNull('next_attempt_at')->orWhere('next_attempt_at','<=',now()))
            ->orderBy('id')->limit($limit)->get();
        if($dryRun) {
            $this->info('Eligible outbox rows: '.$rows->count().'. No emails sent.');
            return self::SUCCESS;
        }
        if(!config('deafjobsindia.alert_delivery_enabled',false)) {
            $this->warn('Delivery disabled. Set JOB_ALERT_DELIVERY_ENABLED=true only after production readiness checks.');
            return self::FAILURE;
        }
        $sent=0; $failed=0;
        foreach($rows as $row) {
            $lock=Cache::lock('job-alert-outbox:'.$row->id,300);
            if(!$lock->get()) continue;
            try {
                $current=DB::table('job_alert_outbox')->where('id',$row->id)->first();
                if(!$current || !in_array($current->status,['pending','failed'],true)) continue;
                $alert=DB::table('job_alerts')->where('id',$current->job_alert_id)->first();
                $user=User::find($current->user_id);
                if(!$alert || !$user || $user->role!=='job_seeker' || !$user->hasVerifiedEmail() || !$alert->enabled || !$alert->email_opt_in) {
                    DB::table('job_alert_outbox')->where('id',$row->id)->update(['status'=>'cancelled','updated_at'=>now()]);
                    continue;
                }
                $ids=json_decode($current->job_ids,true);
                $jobs=JobListing::query()->published()->open()->whereIn('id',is_array($ids)?$ids:[])->get();
                if($jobs->isEmpty()) {
                    DB::table('job_alert_outbox')->where('id',$row->id)->update(['status'=>'cancelled','updated_at'=>now()]);
                    continue;
                }
                $payload=$jobs->map(fn($job)=>[
                    'title'=>$job->title,'location'=>$job->location,'url'=>route('jobs.show',$job->slug),
                ])->all();
                $unsubscribeUrl=\Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'seeker.alerts.unsubscribe.confirm',now()->addDays(30),
                    ['alert'=>$alert->id,'user'=>$user->id]
                );
                Mail::to($user->email)->send(new JobAlertDigest($user->name,$payload,route('seeker.alerts'),$unsubscribeUrl));
                DB::transaction(function() use($current,$jobs) {
                    foreach($jobs as $job) {
                        DB::table('job_alert_deliveries')->insertOrIgnore([
                            'job_alert_id'=>$current->job_alert_id,'job_listing_id'=>$job->id,'created_at'=>now(),
                        ]);
                    }
                    DB::table('job_alerts')->where('id',$current->job_alert_id)->update(['last_sent_at'=>now()]);
                    DB::table('job_alert_outbox')->where('id',$current->id)->update([
                        'status'=>'sent','sent_at'=>now(),'last_error'=>null,'updated_at'=>now(),
                    ]);
                });
                $sent++;
            } catch(\Throwable $e) {
                report($e);
                $failed++;
                $attempts=$row->attempts+1;
                DB::table('job_alert_outbox')->where('id',$row->id)->update([
                    'status'=>$attempts>=5?'exhausted':'failed',
                    'attempts'=>$attempts,
                    'next_attempt_at'=>now()->addMinutes(min(1440,5*(2**min($attempts,8)))),
                    'last_error'=>mb_substr($e->getMessage(),0,500),
                    'updated_at'=>now(),
                ]);
            } finally { $lock->release(); }
        }
        $this->info("Sent: {$sent}; failed: {$failed}");
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
