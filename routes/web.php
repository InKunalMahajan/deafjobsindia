<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ApplicationReviewController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployerController as AdminEmployerController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml',SitemapController::class)->name('sitemap');
Route::get('/robots.txt',fn () => response()->view('seo.robots')->header('Content-Type','text/plain; charset=UTF-8'))->name('robots');
Route::get('/login',fn () => redirect()->route('admin.login'))->name('login');
Route::middleware('guest')->group(function () {
    Route::get('/admin/login',[AdminLoginController::class,'create'])->name('admin.login');
    Route::post('/admin/login',[AdminLoginController::class,'store'])->middleware('throttle:5,1')->name('admin.login.store');
});
Route::post('/admin/logout',[AdminLoginController::class,'destroy'])->middleware('auth')->name('admin.logout');
Route::prefix('admin')->name('admin.')->middleware(['auth','can:admin.view'])->group(function () {
    Route::get('/',DashboardController::class)->name('dashboard');
    Route::resource('employers',AdminEmployerController::class)->except('show')->middleware('can:employers.manage');
    Route::resource('jobs',AdminJobController::class)->except('show')->middleware('can:jobs.edit');
    Route::resource('articles',AdminArticleController::class)->except('show')->middleware('can:articles.edit');
    Route::patch('jobs/{job}/application-review',[ApplicationReviewController::class,'update'])->middleware('can:applications.review')->name('jobs.application-review');
    Route::resource('users',AdminUserController::class)->except('show')->middleware('can:users.manage');
});

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/latest',[NewsController::class,'index'])->name('latest');
Route::get('/employers',[EmployerProfileController::class,'index'])->name('employers.index');
Route::get('/employers/{slug}',[EmployerProfileController::class,'show'])->name('employers.show');
Route::get('/jobs',[JobController::class,'index'])->name('jobs.index');
Route::get('/jobs/{slug}',[JobController::class,'show'])->name('jobs.show');
Route::view('/search','pages.search')->name('search');
Route::get('/news/{slug}',[NewsController::class,'show'])->name('news.show');
Route::get('/{section}',function (string $section,NewsController $news) {
    $categories=['education'=>'Education','training'=>'Training','career'=>'Career'];
    abort_unless(array_key_exists($section,$categories),404);
    return $news->index($categories[$section]);
})->whereIn('section',['education','training','career'])->name('section.show');
foreach ([
    'schemes'=>'Government Schemes','job-fairs'=>'Job Fairs','community'=>'Community',
    'technology'=>'Technology','government-jobs'=>'Jobs','deaf-jobs'=>'Jobs',
    'pwd-jobs'=>'Jobs','maharashtra-jobs'=>'Jobs','mpsc-jobs'=>'Jobs',
    'mumbai-jobs'=>'Jobs','pune-jobs'=>'Jobs','railway-jobs'=>'Jobs',
    'ssc-jobs'=>'Jobs','upsc-jobs'=>'Jobs',
] as $path=>$category) {
    Route::get('/'.$path,fn (NewsController $news)=>$news->index($category));
}
Route::view('/about','pages.about')->name('about');
Route::view('/contact','pages.contact')->name('contact');
Route::view('/privacy','pages.privacy')->name('privacy');
Route::view('/disclaimer','pages.disclaimer')->name('disclaimer');
Route::view('/advertise','pages.advertise')->name('advertise');
Route::get('/rss.xml',function () {
    return response()->view('feeds.rss',['articles'=>Article::published()->latest('published_at')->limit(25)->get()])
        ->header('Content-Type','application/rss+xml; charset=UTF-8');
})->name('rss');
Route::get('/design-system',function () {
    abort_unless(app()->environment('local','testing'),404);
    return view('pages.design-system');
})->name('design-system');
