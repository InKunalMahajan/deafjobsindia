<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class EmployerProfileController extends Controller
{
    public function index(): View
    {
        $employers = Employer::query()
            ->where('verified', true)
            ->withCount(['jobs as open_jobs_count' => fn (Builder $query) =>
                $query->published()->open()])
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(12);

        return view('pages.employers', compact('employers'));
    }

    public function show(string $slug): View
    {
        $employer = Employer::query()
            ->where('verified', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $jobs = $employer->jobs()
            ->with('employer')
            ->published()
            ->open()
            ->latest('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('pages.employer-profile', compact('employer', 'jobs'));
    }
}
