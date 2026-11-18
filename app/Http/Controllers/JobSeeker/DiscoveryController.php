<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DiscoveryController extends Controller {
    public function recommendations(Request $request): View {
        $user=$request->user();
        $skills=array_slice(array_filter(array_map('trim',explode(',',$user->skills ?? ''))),0,10);
        $jobs=JobListing::query()->published()->open()->with('employer')
            ->when($user->preferred_location,fn($q)=>$q->where('location','like','%'.$user->preferred_location.'%'))
            ->when($user->preferred_work_mode,fn($q)=>$q->where('work_mode',$user->preferred_work_mode))
            ->when(count($skills),function($q) use($skills) {
                $q->where(function($q) use($skills) {
                    foreach($skills as $skill) {
                        $q->orWhere('title','like','%'.$skill.'%')
                          ->orWhere('description','like','%'.$skill.'%');
                    }
                });
            })
            ->latest('published_at')->paginate(12);
        return view('job-seeker.recommendations',compact('jobs'));
    }
    public function alerts(Request $request): View {
        $alerts=DB::table('job_alerts')->where('user_id',$request->user()->id)->latest()->get();
        return view('job-seeker.alerts',compact('alerts'));
    }
    public function storeAlert(Request $request): RedirectResponse {
        $data=$request->validate([
            'keywords'=>['nullable','string','max:150'],
            'location'=>['nullable','string','max:150'],
        ]);
        if(blank($data['keywords'] ?? null) && blank($data['location'] ?? null)) {
            return back()->withErrors(['keywords'=>'Enter keywords or a location.']);
        }
        abort_if(DB::table('job_alerts')->where('user_id',$request->user()->id)->count()>=10,422);
        DB::table('job_alerts')->insert([...$data,'user_id'=>$request->user()->id,'enabled'=>true,'created_at'=>now(),'updated_at'=>now()]);
        return back()->with('status','Job alert saved. Email delivery is not yet enabled.');
    }
    public function deleteAlert(Request $request,int $alert): RedirectResponse {
        DB::table('job_alerts')->where('user_id',$request->user()->id)->where('id',$alert)->delete();
        return back()->with('status','Job alert removed.');
    }
}
