<?php

namespace App\Http\Controllers\EmployerPortal;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicationsController extends Controller {
    private function ownedJob(Request $request,JobListing $job): void {
        abort_unless(DB::table('employer_memberships')
            ->where('user_id',$request->user()->id)
            ->where('employer_id',$job->employer_id)->exists(),403);
    }
    public function index(Request $request,JobListing $job): View {
        $this->ownedJob($request,$job);
        $applications=DB::table('recruitment_applications')
            ->where('job_listing_id',$job->id)->latest()->paginate(20);
        $candidates=\App\Models\User::whereIn('id',$applications->pluck('user_id'))
            ->get(['id','name','headline','education','skills'])->keyBy('id');
        return view('employer-portal.applications',compact('job','applications','candidates'));
    }
    public function update(Request $request,JobListing $job,int $application): RedirectResponse {
        $this->ownedJob($request,$job);
        $data=$request->validate([
            'status'=>['required',Rule::in(['submitted','reviewing','shortlisted','interview','offer','rejected'])],
            'interview_at'=>['nullable','date','after:now'],
            'interview_instructions'=>['nullable','string','max:2000'],
        ]);
        if($data['status']!=='interview') {
            $data['interview_at']=null;
            $data['interview_instructions']=null;
        }
        $updated=DB::table('recruitment_applications')->where('job_listing_id',$job->id)
            ->where('id',$application)->update([...$data,'updated_at'=>now()]);
        abort_unless($updated,404);
        return back()->with('status','Candidate application updated.');
    }
}
