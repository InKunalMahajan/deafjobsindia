<?php

namespace App\Http\Controllers\EmployerPortal;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller {
    private function company(Request $request): Employer {
        $id=DB::table('employer_memberships')->where('user_id',$request->user()->id)
            ->orderBy('id')->value('employer_id');
        abort_unless($id,403);
        return Employer::findOrFail($id);
    }
    public function index(Request $request): View {
        $company=$this->company($request);
        $jobs=JobListing::where('employer_id',$company->id)->latest()->paginate(15);
        return view('employer-portal.dashboard',compact('company','jobs'));
    }
    public function storeJob(Request $request): RedirectResponse {
        $company=$this->company($request);
        $data=$request->validate([
            'title'=>['required','string','max:255'],
            'location'=>['required','string','max:255'],
            'employment_type'=>['required',Rule::in(['full-time','part-time','contract','internship'])],
            'work_mode'=>['required',Rule::in(['onsite','hybrid','remote'])],
            'summary'=>['required','string','max:2000'],
            'description'=>['required','string','max:50000'],
            'requirements'=>['nullable','string','max:20000'],
            'apply_url'=>['required','url','starts_with:https://','max:2048'],
            'deadline'=>['nullable','date','after_or_equal:today'],
        ]);
        JobListing::create([
            ...$data,
            'employer_id'=>$company->id,
            'slug'=>Str::slug($data['title']).'-'.Str::lower(Str::random(12)),
            'sector'=>'private',
            'source_url'=>$data['apply_url'],
            'application_method'=>'external',
            'status'=>'draft',
            'published_at'=>null,
            'is_deaf_friendly'=>false,
            'is_pwd_eligible'=>false,
        ]);
        return redirect()->route('employer.dashboard')->with('status','Job draft submitted for administrator review. It is not publicly visible.');
    }
}
