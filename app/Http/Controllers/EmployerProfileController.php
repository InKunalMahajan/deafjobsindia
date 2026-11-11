<?php
namespace App\Http\Controllers;
use App\Models\Employer;
use Illuminate\View\View;
class EmployerProfileController extends Controller {
 public function index(): View {
  return view('pages.employers',['employers'=>Employer::where('verified',true)->orderBy('name')->paginate(12)]);
 }
 public function show(string $slug): View {
  $employer=Employer::where('verified',true)->where('slug',$slug)->firstOrFail();
  $jobs=$employer->jobs()->published()->open()->latest('published_at')->paginate(12);
  return view('pages.employer-profile',compact('employer','jobs'));
 }
}
