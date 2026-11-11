<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ApplicationReviewController extends Controller {
 public function update(Request $request,JobListing $job): RedirectResponse {
  $data=$request->validate([
   'application_review_status'=>['required',Rule::in(['unreviewed','verified','needs_update'])],
   'application_method'=>['required',Rule::in(['external'])],
   'application_instructions'=>['nullable','string','max:5000'],
  ]);
  $job->update([...$data,'application_reviewed_at'=>now(),
   'application_reviewed_by'=>$request->user()->id]);
  return back()->with('status','Application guidance reviewed.');
 }
}
