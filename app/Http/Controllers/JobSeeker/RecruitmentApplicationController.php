<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RecruitmentApplicationController extends Controller {
    public function index(Request $request): View {
        $applications=DB::table('recruitment_applications')
            ->where('user_id',$request->user()->id)->latest()->paginate(15);
        $jobs=JobListing::with('employer')->whereIn('id',$applications->pluck('job_listing_id'))->get()->keyBy('id');
        return view('job-seeker.submitted-applications',compact('applications','jobs'));
    }

    public function store(Request $request,JobListing $job): RedirectResponse {
        abort_unless($job->status==='published' && $job->published_at?->lte(now()) && !$job->is_closed,404);
        abort_unless($job->application_method==='internal',404);
        $data=$request->validate([
            'cover_note'=>['nullable','string','max:3000'],
            'consent'=>['accepted'],
        ]);
        $exists=DB::table('recruitment_applications')->where('user_id',$request->user()->id)
            ->where('job_listing_id',$job->id)->exists();
        if($exists) return back()->withErrors(['application'=>'You have already applied for this job.']);
        DB::table('recruitment_applications')->insert([
            'user_id'=>$request->user()->id,'job_listing_id'=>$job->id,
            'cover_note'=>$data['cover_note'] ?? null,'status'=>'submitted',
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        return redirect()->route('seeker.submitted')->with('status','Application submitted to the employer through DeafJobsIndia.');
    }
}
