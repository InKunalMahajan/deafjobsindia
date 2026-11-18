<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller {
    public function index(Request $request): View {
        $user=$request->user();
        return view('job-seeker.dashboard',[
            'saved'=>DB::table('saved_jobs')->where('user_id',$user->id)->pluck('job_listing_id')->all(),
            'tracked'=>DB::table('job_application_trackers')->where('user_id',$user->id)->get()->keyBy('job_listing_id'),
            'jobs'=>JobListing::whereIn('id',array_unique(array_merge(
                DB::table('saved_jobs')->where('user_id',$user->id)->pluck('job_listing_id')->all(),
                DB::table('job_application_trackers')->where('user_id',$user->id)->pluck('job_listing_id')->all()
            )))->with('employer')->get(),
        ]);
    }
    public function profile(Request $request): View { return view('job-seeker.profile',['user'=>$request->user()]); }
    public function updateProfile(Request $request): RedirectResponse {
        $user=$request->user();
        $data=$request->validate([
            'name'=>['required','string','max:255'],
            'location'=>['nullable','string','max:255'],
            'bio'=>['nullable','string','max:2000'],
        ]);
        $user->update($data);
        return back()->with('status','Profile updated.');
    }
    public function save(Request $request,JobListing $job): RedirectResponse {
        abort_unless($job->status==='published' && $job->published_at?->lte(now()),404);
        DB::table('saved_jobs')->insertOrIgnore([
            'user_id'=>$request->user()->id,'job_listing_id'=>$job->id,
            'created_at'=>now(),'updated_at'=>now(),
        ]);
        return back()->with('status','Job saved.');
    }
    public function unsave(Request $request,JobListing $job): RedirectResponse {
        DB::table('saved_jobs')->where('user_id',$request->user()->id)->where('job_listing_id',$job->id)->delete();
        return back()->with('status','Saved job removed.');
    }
    public function track(Request $request,JobListing $job): RedirectResponse {
        $data=$request->validate([
            'status'=>['required',Rule::in(['interested','applied','interview','offer','rejected','withdrawn'])],
            'applied_at'=>['nullable','date'],
            'notes'=>['nullable','string','max:2000'],
        ]);
        DB::table('job_application_trackers')->updateOrInsert(
            ['user_id'=>$request->user()->id,'job_listing_id'=>$job->id],
            [...$data,'updated_at'=>now()]
        );
        return back()->with('status','Application tracker updated.');
    }
}
