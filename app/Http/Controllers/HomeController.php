<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\JobListing;
use Illuminate\Contracts\View\View;
class HomeController extends Controller {
 public function index(): View {
  return view('pages.home',[
   'jobs'=>JobListing::with('employer')->published()->open()->latest('published_at')->limit(6)->get(),
   'articles'=>Article::published()->latest('published_at')->limit(3)->get(),
  ]);
 }
}