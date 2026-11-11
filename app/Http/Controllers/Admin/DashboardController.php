<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\View\View;
class DashboardController extends Controller {
    public function __invoke(): View {
        return view('admin.dashboard',[
            'employers'=>Employer::count(),
            'jobs'=>JobListing::count(),
            'published'=>JobListing::published()->count(),
            'drafts'=>JobListing::where('status','draft')->count(),
        ]);
    }
}
