<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationReviewController extends Controller
{
    public function update(Request $request, JobListing $job): RedirectResponse
    {
        $data = $request->validate([
            'application_review_status' => ['required', Rule::in(['unreviewed', 'verified', 'needs_update'])],
            'application_method' => ['required', Rule::in(['external'])],
            'application_instructions' => ['nullable', 'string', 'max:5000'],
        ]);

        // Reset stale reviewer metadata when a review is withdrawn.
        $isReviewed = $data['application_review_status'] !== 'unreviewed';

        $job->update([
            ...$data,
            'application_reviewed_at' => $isReviewed ? now() : null,
            'application_reviewed_by' => $isReviewed ? $request->user()->id : null,
        ]);

        return back()->with('status', 'Application guidance reviewed.');
    }
}
