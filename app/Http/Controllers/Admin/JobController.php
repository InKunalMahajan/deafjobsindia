<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class JobController extends Controller {
    public function index(): View { return view('admin.jobs.index',['jobs'=>JobListing::with('employer')->latest()->paginate(15)]); }
    public function create(): View { return view('admin.jobs.form',['job'=>new JobListing(),'employers'=>Employer::orderBy('name')->get()]); }
    public function store(Request $request): RedirectResponse {
        JobListing::create($this->validated($request));
        return redirect()->route('admin.jobs.index')->with('status','Job created.');
    }
    public function edit(JobListing $job): View {
        return view('admin.jobs.form',['job'=>$job,'employers'=>Employer::orderBy('name')->get()]);
    }
    public function update(Request $request,JobListing $job): RedirectResponse {
        $job->update($this->validated($request,$job));
        return redirect()->route('admin.jobs.index')->with('status','Job updated.');
    }
    public function destroy(JobListing $job): RedirectResponse {
        Gate::authorize('jobs.publish');
        $job->delete();
        return back()->with('status','Job deleted.');
    }
    private function validated(Request $request,?JobListing $job=null): array {
        if ($job?->status==='published') Gate::authorize('jobs.publish');
        $data=$request->validate([
            'employer_id'=>['required','exists:employers,id'],
            'slug'=>['required','alpha_dash','max:255',Rule::unique('job_listings','slug')->ignore($job?->id)],
            'title'=>['required','string','max:255'],
            'location'=>['required','string','max:255'],
            'state'=>['nullable','string','max:255'],
            'employment_type'=>['required',Rule::in(['full-time','part-time','contract','internship'])],
            'work_mode'=>['required',Rule::in(['onsite','hybrid','remote'])],
            'sector'=>['required',Rule::in(['government','private','nonprofit'])],
            'summary'=>['required','string','max:2000'],
            'description'=>['required','string','max:50000'],
            'requirements'=>['nullable','string','max:20000'],
            'salary_text'=>['nullable','string','max:255'],
            'source_url'=>['required','url','starts_with:https://','max:2048'],
            'apply_url'=>['required','url','starts_with:https://','max:2048'],
            'deadline'=>['nullable','date'],
            'status'=>['required',Rule::in(['draft','published'])],
            'is_deaf_friendly'=>['sometimes','boolean'],
            'is_pwd_eligible'=>['sometimes','boolean'],
        ]);
        $data['is_deaf_friendly']=$request->boolean('is_deaf_friendly');
        $data['is_pwd_eligible']=$request->boolean('is_pwd_eligible');
        if ($data['status']==='published') {
            Gate::authorize('jobs.publish');
            $data['published_at']=$job?->published_at ?? now();
        } else {
            // Editors cannot unpublish already published records.
            if ($job?->status==='published') Gate::authorize('jobs.publish');
            $data['published_at']=null;
        }
        return $data;
    }
}
