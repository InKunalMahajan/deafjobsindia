<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Contracts\View\View;

class JobController extends Controller {
    public function index(): View { return view('pages.jobs'); }

    public function show(string $slug): View {
        $job = JobListing::query()->with('employer')->published()
            ->where('slug',$slug)->firstOrFail();

        return view('pages.job-detail',compact('job'));
    }
}
