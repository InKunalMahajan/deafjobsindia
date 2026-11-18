<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'employers' => Employer::count(),
            'verifiedEmployers' => Employer::where('verified', true)->count(),
            'pendingEmployers' => Employer::where('verified', false)->count(),
            'jobs' => JobListing::count(),
            'published' => JobListing::published()->count(),
            'openJobs' => JobListing::published()->open()->count(),
            'drafts' => JobListing::where('status', 'draft')->count(),
            'jobsNeedingReview' => JobListing::where('application_review_status', 'needs_update')->count(),
            'jobsUnreviewed' => JobListing::where('application_review_status', 'unreviewed')->count(),
            'articles' => Article::count(),
            'publishedArticles' => Article::published()->count(),
            'draftArticles' => Article::where('status', 'draft')->count(),
        ]);
    }
}
