<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AlertUnsubscribeController extends Controller {
    public function confirm(Request $request,int $alert,int $user): View {
        $exists=DB::table('job_alerts')->where('id',$alert)->where('user_id',$user)->exists();
        abort_unless($exists,404);
        return view('job-seeker.alert-unsubscribe',compact('alert','user'));
    }

    public function unsubscribe(Request $request,int $alert,int $user): RedirectResponse {
        DB::transaction(function() use($alert,$user) {
            DB::table('job_alerts')->where('id',$alert)->where('user_id',$user)
                ->update(['email_opt_in'=>false,'updated_at'=>now()]);
            DB::table('job_alert_outbox')->where('job_alert_id',$alert)
                ->where('user_id',$user)->whereIn('status',['pending','failed','exhausted'])
                ->update(['status'=>'cancelled','updated_at'=>now()]);
        });
        return redirect()->route('seeker.alerts.unsubscribe.done');
    }
}
