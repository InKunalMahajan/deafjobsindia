<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResumeController extends Controller {
    public function store(Request $request): RedirectResponse {
        $request->validate(['resume'=>['required',File::types(['pdf','doc','docx'])->max('3mb')]]);
        $user=$request->user();
        $file=$request->file('resume');
        $path=$file->store('resumes/'.$user->id,'local');
        $old=$user->resume_path;
        $user->forceFill([
            'resume_path'=>$path,
            'resume_original_name'=>basename($file->getClientOriginalName()),
        ])->save();
        if($old) Storage::disk('local')->delete($old);
        return back()->with('status','Resume uploaded privately.');
    }
    public function download(Request $request): BinaryFileResponse {
        $user=$request->user();
        abort_unless($user->resume_path && Storage::disk('local')->exists($user->resume_path),404);
        return response()->download(Storage::disk('local')->path($user->resume_path),$user->resume_original_name ?: 'resume.pdf');
    }
    public function destroy(Request $request): RedirectResponse {
        $user=$request->user();
        if($user->resume_path) Storage::disk('local')->delete($user->resume_path);
        $user->forceFill(['resume_path'=>null,'resume_original_name'=>null])->save();
        return back()->with('status','Resume deleted.');
    }
}
