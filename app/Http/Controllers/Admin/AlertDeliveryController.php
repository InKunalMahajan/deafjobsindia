<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AlertDeliveryController extends Controller {
    public function index(Request $request): View {
        $status=$request->query('status');
        $allowed=['pending','failed','exhausted','sent','cancelled'];
        abort_if($status!==null && !in_array($status,$allowed,true),422);
        $counts=DB::table('job_alert_outbox')->select('status')
            ->selectRaw('COUNT(*) as total')->groupBy('status')->pluck('total','status');
        $deliveries=DB::table('job_alert_outbox')
            ->when($status,fn($query)=>$query->where('status',$status))
            ->select('id','job_alert_id','status','attempts','created_at','updated_at','sent_at','next_attempt_at')
            ->orderByDesc('id')->paginate(25)->withQueryString();
        return view('admin.alert-deliveries',compact('counts','deliveries','status'));
    }

    public function retry(Request $request,int $delivery): RedirectResponse {
        $changed=DB::transaction(function() use($delivery,$request) {
            $changed=DB::table('job_alert_outbox')->where('id',$delivery)
                ->whereIn('status',['failed','exhausted'])
                ->update([
                    'status'=>'pending','attempts'=>0,'next_attempt_at'=>null,
                    'last_error'=>null,'updated_at'=>now(),
                ]);
            if($changed) DB::table('alert_delivery_audits')->insert([
                'delivery_id'=>$delivery,'actor_id'=>$request->user()->id,
                'action'=>'retry_queued','created_at'=>now(),
            ]);
            return $changed;
        });
        abort_unless($changed,404);
        // Retry only queues an outbox row. No mail is sent by this HTTP request.
        return back()->with('status','Delivery queued for a future attempt. Email delivery remains controlled by server settings.');
    }
}
