<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class PrivacyController extends Controller {
    public function index(Request $request): View {
        $user=$request->user();
        return view('job-seeker.privacy',compact('user'));
    }

    public function export(Request $request): StreamedResponse {
        $user=$request->user();
        $id=$user->id;
        $data=[
            'exported_at'=>now()->toIso8601String(),
            'profile'=>$user->only([
                'name','email','headline','education','experience_years','skills',
                'location','preferred_location','preferred_work_mode','requires_captions','prefers_isl',
                'bio','resume_original_name',
            ]),
            'saved_jobs'=>DB::table('saved_jobs')->where('user_id',$id)->get()->toArray(),
            'manual_applications'=>DB::table('job_application_trackers')->where('user_id',$id)->get()->toArray(),
            'manual_application_history'=>DB::table('job_application_status_history')->where('user_id',$id)->get()->toArray(),
            'submitted_applications'=>DB::table('recruitment_applications')->where('user_id',$id)->get()->toArray(),
            'job_alerts'=>DB::table('job_alerts')->where('user_id',$id)->get()->toArray(),
        ];
        return response()->streamDownload(function() use($data) {
            echo json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
        },'deafjobsindia-my-data.json',[
            'Content-Type'=>'application/json; charset=UTF-8',
            'Cache-Control'=>'no-store, private',
            'X-Content-Type-Options'=>'nosniff',
        ]);
    }
}
