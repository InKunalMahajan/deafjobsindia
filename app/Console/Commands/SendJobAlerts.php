<?php

namespace App\Console\Commands;

use App\Mail\JobAlertDigest;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SendJobAlerts extends Command {
    protected $signature='jobs:send-alerts {--dry-run : Count matches without sending email}';
    protected $description='Send opt-in job alerts at the selected frequency';

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
                    $query=JobListing::query()->published()->open()
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
                    $payload=$jobs->map(fn($job)=>[
                        'title'=>$job->title,'location'=>$job->location,
                        'url'=>route('jobs.show',$job->slug),
                    ])->all();
                    try {
                        Mail::to($user->email)->send(new JobAlertDigest(
                            $user->name,$payload,route('seeker.alerts')
                        ));
                        DB::transaction(function() use($alert,$jobs) {
                            foreach($jobs as $job) {
                                DB::table('job_alert_deliveries')->insertOrIgnore([
                                    'job_alert_id'=>$alert->id,'job_listing_id'=>$job->id,'created_at'=>now(),
                                ]);
                            }
                            DB::table('job_alerts')->where('id',$alert->id)->update(['last_sent_at'=>now()]);
                        });
                    } catch(\Throwable $e) {
                        report($e);
                        $failed++;
                        $this->error('Unable to send alert '.$alert->id);
                    }
                    } finally {
                        if($lock) $lock->release();
                    }
                }
            });
        $this->info(($dryRun ? 'Matched alerts: ' : 'Attempted alert digests: ').$processed);
        if(!$dryRun) $this->info('Failed: '.$failed.'; skipped (locked): '.$skipped);
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
