<?php

namespace App\Console\Commands;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SendJobAlerts extends Command {
    protected $signature='jobs:send-alerts {--dry-run : Count matches without sending email}';
    protected $description='Stage opt-in job alert digests in the durable outbox (does not send email)';

    public function handle(): int {
        $dryRun=(bool)$this->option('dry-run');
        $processed=0;
        $failed=0;
        $skipped=0;
        DB::table('job_alerts')->where('enabled',true)->where('email_opt_in',true)
            ->orderBy('id')->chunkById(100,function($alerts) use($dryRun,&$processed,&$failed,&$skipped) {
                foreach($alerts as $alert) {
                    // A shared cache lock prevents concurrent workers sending the same alert.
                    // Dry runs never acquire locks or modify delivery state.
                    $lock=$dryRun ? null : Cache::lock('job-alert-digest:'.$alert->id,300);
                    if($lock && !$lock->get()) { $skipped++; continue; }
                    try {
                    $user=User::find($alert->user_id);
                    if(!$user || $user->role!=='job_seeker' || !$user->email_verified_at) continue;
                    $hours=$alert->frequency==='weekly' ? 168 : 24;
                    if($alert->last_sent_at && \Illuminate\Support\Carbon::parse($alert->last_sent_at)->diffInMinutes(now())<$hours*60) continue;
                    $reservedIds=DB::table('job_alert_outbox')
                        ->where('job_alert_id',$alert->id)
                        ->whereIn('status',['pending','failed','exhausted','sent'])
                        ->pluck('job_ids')->flatMap(function($json) {
                            $ids=json_decode($json,true);
                            return is_array($ids) ? $ids : [];
                        })->all();
                    $query=JobListing::query()->published()->open()
                        ->whereNotIn('id',$reservedIds)
                        ->whereNotIn('id',DB::table('job_alert_deliveries')->select('job_listing_id')->where('job_alert_id',$alert->id));
                    if(filled($alert->location)) $query->where('location','like','%'.$alert->location.'%');
                    if(filled($alert->keywords)) {
                        $words=array_slice(array_filter(preg_split('/\s+/',trim($alert->keywords))),0,8);
                        $query->where(function($q) use($words) {
                            foreach($words as $word) {
                                $escaped=addcslashes($word,'%_\\');
                                $q->orWhere('title','like','%'.$escaped.'%')
                                  ->orWhere('description','like','%'.$escaped.'%');
                            }
                        });
                    }
                    $jobs=$query->orderBy('published_at')->limit(10)->get();
                    if($jobs->isEmpty()) continue;
                    $processed++;
                    if($dryRun) continue;
                    try {
                        $ids=$jobs->pluck('id')->sort()->values()->all();
                        $key=hash('sha256',$alert->id.':'.implode(',',$ids));
                        DB::table('job_alert_outbox')->insertOrIgnore([
                            'job_alert_id'=>$alert->id,'user_id'=>$user->id,
                            'dedupe_key'=>$key,'job_ids'=>json_encode($ids),
                            'status'=>'pending','created_at'=>now(),'updated_at'=>now(),
                        ]);
                    } catch(\Throwable $e) {
                        report($e);
                        $failed++;
                        $this->error('Unable to stage alert '.$alert->id);
                    }
                    } finally {
                        if($lock) $lock->release();
                    }
                }
            });
        $this->info(($dryRun ? 'Matched alerts: ' : 'Staged alert digests: ').$processed);
        if(!$dryRun) $this->info('Failed: '.$failed.'; skipped (locked): '.$skipped);
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
