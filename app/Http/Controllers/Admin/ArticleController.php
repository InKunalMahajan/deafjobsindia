<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class ArticleController extends Controller {
 public function index(): View {return view('admin.articles.index',['articles'=>Article::latest()->paginate(20)]);}
 public function create(): View {return view('admin.articles.form',['article'=>new Article()]);}
 public function store(Request $request): RedirectResponse {
  Article::create($this->validated($request));
  return redirect()->route('admin.articles.index')->with('status','Article saved.');
 }
 public function edit(Article $article): View {return view('admin.articles.form',compact('article'));}
 public function update(Request $request,Article $article): RedirectResponse {
  if($article->status==='published') Gate::authorize('articles.publish');
  $article->update($this->validated($request,$article));
  return redirect()->route('admin.articles.index')->with('status','Article updated.');
 }
 public function destroy(Article $article): RedirectResponse {
  Gate::authorize('articles.publish');$article->delete();
  return back()->with('status','Article deleted.');
 }
 private function validated(Request $request,?Article $article=null): array {
  $data=$request->validate([
   'title'=>['required','string','max:255'],
   'slug'=>['required','alpha_dash','max:255',Rule::unique('articles','slug')->ignore($article?->id)],
   'description'=>['required','string','max:2000'],
   'category'=>['required','string','max:100'],
   'body'=>['required','string','max:100000'],
   'source_name'=>['nullable','string','max:255'],
   'source_url'=>['nullable','url','starts_with:https://','max:2048'],
   'status'=>['required',Rule::in(['draft','published'])],
  ]);
  $data['is_demo']=false;
  if($data['status']==='published'){
   Gate::authorize('articles.publish');
   $data['published_at']=$article?->published_at ?? now();
  }else{$data['published_at']=null;}
  return $data;
 }
}
