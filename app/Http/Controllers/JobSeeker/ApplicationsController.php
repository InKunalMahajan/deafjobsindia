<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ApplicationsController extends Controller {
    public function index(Request $request): View {
        $status=$request->query('status','');
        abort_unless(is_string($status),422);
        $allowed=['interested','applied','interview','offer','rejected','withdrawn'];
        abort_unless($status==='' || in_array($status,$allowed,true),422);
        $tracked=DB::table('job_application_trackers')
            ->where('user_id',$request->user()->id)
            ->when($status!=='',fn($q)=>$q->where('status',$status))
            ->orderByDesc('updated_at')->paginate(15)->withQueryString();
        $jobs=JobListing::with('employer')->whereIn('id',$tracked->pluck('job_listing_id'))->get()->keyBy('id');
        return view('job-seeker.applications',compact('tracked','jobs','status'));
    }
    public function history(Request $request,JobListing $job): View {
        $tracker=DB::table('job_application_trackers')
            ->where('user_id',$request->user()->id)->where('job_listing_id',$job->id)->first();
        abort_unless($tracker,404);
        $history=DB::table('job_application_status_history')
            ->where('user_id',$request->user()->id)->where('job_listing_id',$job->id)
            ->orderByDesc('id')->paginate(20);
        return view('job-seeker.application-history',compact('job','tracker','history'));
    }
}
