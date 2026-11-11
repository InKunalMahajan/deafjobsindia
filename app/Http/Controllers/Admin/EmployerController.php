<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Employer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class EmployerController extends Controller {
    public function index(): View { return view('admin.employers.index',['employers'=>Employer::latest()->paginate(15)]); }
    public function create(): View { return view('admin.employers.form',['employer'=>new Employer()]); }
    public function store(Request $request): RedirectResponse {
        Employer::create($this->validated($request));
        return redirect()->route('admin.employers.index')->with('status','Employer created.');
    }
    public function edit(Employer $employer): View { return view('admin.employers.form',compact('employer')); }
    public function update(Request $request,Employer $employer): RedirectResponse {
        $employer->update($this->validated($request,$employer));
        return redirect()->route('admin.employers.index')->with('status','Employer updated.');
    }
    public function destroy(Employer $employer): RedirectResponse {
        if ($employer->jobs()->exists()) return back()->withErrors(['employer'=>'Cannot delete an employer with job listings.']);
        $employer->delete();
        return back()->with('status','Employer deleted.');
    }
    private function validated(Request $request,?Employer $employer=null): array {
        $data=$request->validate([
            'name'=>['required','string','max:255'],
            'slug'=>['required','alpha_dash','max:255',Rule::unique('employers','slug')->ignore($employer?->id)],
            'website'=>['nullable','url','starts_with:https://','max:2048'],
            'description'=>['nullable','string','max:5000'],
            'verified'=>['sometimes','boolean'],
        ]);
        $data['verified']=$request->boolean('verified');
        return $data;
    }
}
