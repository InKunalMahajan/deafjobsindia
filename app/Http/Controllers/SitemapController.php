<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\Http\Response;
class SitemapController extends Controller {
 public function __invoke(): Response {
  $urls=[['loc'=>route('home')],['loc'=>route('jobs.index')],['loc'=>route('latest')],['loc'=>route('employers.index')]];
  foreach(Article::published()->pluck('slug') as $slug)$urls[]=['loc'=>route('news.show',$slug)];
  foreach(JobListing::published()->open()->pluck('slug') as $slug)$urls[]=['loc'=>route('jobs.show',$slug)];
  foreach(Employer::where('verified',true)->pluck('slug') as $slug)$urls[]=['loc'=>route('employers.show',$slug)];
  return response()->view('seo.sitemap',compact('urls'))->header('Content-Type','application/xml; charset=UTF-8')->header('Cache-Control','public, max-age=900');
 }
}