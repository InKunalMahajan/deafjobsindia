<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AccountRecoveryController;
use App\Http\Controllers\Auth\AccountVerificationController;
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
use App\Http\Controllers\JobSeeker\AuthController as SeekerAuthController;
use App\Http\Controllers\JobSeeker\DashboardController as SeekerDashboardController;
use App\Http\Controllers\JobSeeker\GoogleController as SeekerGoogleController;
use App\Models\JobListing;
use App\Http\Controllers\JobSeeker\PrivacyController as SeekerPrivacyController;
use App\Http\Controllers\JobSeeker\RecruitmentApplicationController as SeekerRecruitmentController;
use App\Http\Controllers\EmployerPortal\ApplicationsController as EmployerApplicationsController;
use App\Http\Controllers\EmployerPortal\AuthController as EmployerAuthController;
use App\Http\Controllers\EmployerPortal\DashboardController as EmployerDashboardController;
use App\Http\Controllers\JobSeeker\ApplicationsController as SeekerApplicationsController;
use App\Http\Controllers\JobSeeker\ResumeController as SeekerResumeController;
use App\Http\Controllers\JobSeeker\DiscoveryController as SeekerDiscoveryController;
use App\Http\Controllers\JobSeeker\AlertUnsubscribeController;
use Illuminate\Support\Facades\Route;

// Separate public recovery routes for job seekers and employers.
foreach(['seeker'=>'account','employer'=>'employer-account'] as $portal=>$prefix) {
    Route::prefix($prefix)->name($portal.'.')->middleware('guest')->group(function() use($portal) {
        Route::get('/forgot-password',fn(AccountRecoveryController $controller)=>$controller->requestForm($portal))->name('password.request');
        Route::post('/forgot-password',fn(\Illuminate\Http\Request $request,AccountRecoveryController $controller)=>$controller->sendLink($request,$portal))
            ->middleware('throttle:5,1')->name('password.email');
        Route::get('/reset-password/{token}',fn(\Illuminate\Http\Request $request,AccountRecoveryController $controller,string $token)=>$controller->resetForm($request,$portal,$token))->name('password.reset');
        Route::post('/reset-password',fn(\Illuminate\Http\Request $request,AccountRecoveryController $controller)=>$controller->reset($request,$portal))
            ->middleware('throttle:5,1')->name('password.update');
    });
}
Route::middleware('auth')->group(function() {
    Route::get('/email/verify',[AccountVerificationController::class,'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',[AccountVerificationController::class,'verify'])
        ->middleware(['signed','throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification',[AccountVerificationController::class,'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});

// Signed email links permit a limited unsubscribe without requiring login.
Route::get('/alerts/unsubscribe/{alert}/{user}',[AlertUnsubscribeController::class,'confirm'])
    ->middleware(['signed','throttle:20,1'])->name('seeker.alerts.unsubscribe.confirm');
Route::post('/alerts/unsubscribe/{alert}/{user}',[AlertUnsubscribeController::class,'unsubscribe'])
    ->middleware(['signed','throttle:20,1'])->name('seeker.alerts.unsubscribe.apply');
Route::view('/alerts/unsubscribed','job-seeker.alert-unsubscribe-done')->name('seeker.alerts.unsubscribe.done');

// Employer portal is separate from both administrator and job-seeker routes.
Route::prefix('employer-account')->name('employer.')->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/register',[EmployerAuthController::class,'registerForm'])->name('register');
        Route::post('/register',[EmployerAuthController::class,'register'])->middleware('throttle:5,1')->name('register.store');
        Route::get('/login',[EmployerAuthController::class,'loginForm'])->name('login');
        Route::post('/login',[EmployerAuthController::class,'login'])->middleware('throttle:5,1')->name('login.store');
    });
    Route::middleware(['auth','can:employer.view'])->group(function() {
        Route::get('/',[EmployerDashboardController::class,'index'])->name('dashboard');
        Route::post('/jobs',[EmployerDashboardController::class,'storeJob'])->middleware('throttle:10,1')->name('jobs.store');
        Route::get('/jobs/{job}/applications',[EmployerApplicationsController::class,'index'])->name('applications');
        Route::patch('/jobs/{job}/applications/{application}',[EmployerApplicationsController::class,'update'])->middleware('throttle:30,1')->name('applications.update');
        Route::post('/logout',[EmployerAuthController::class,'logout'])->name('logout');
    });
});

// Job-seeker authentication is separate from the existing /admin/login routes.
Route::prefix('account')->name('seeker.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register',[SeekerAuthController::class,'registerForm'])->name('register');
        Route::post('/register',[SeekerAuthController::class,'register'])->middleware('throttle:5,1')->name('register.store');
        Route::get('/login',[SeekerAuthController::class,'loginForm'])->name('login');
        Route::post('/login',[SeekerAuthController::class,'login'])->middleware('throttle:5,1')->name('login.store');
        Route::get('/google',[SeekerGoogleController::class,'redirect'])->middleware('throttle:10,1')->name('google.redirect');
        Route::get('/google/callback',[SeekerGoogleController::class,'callback'])->middleware('throttle:10,1')->name('google.callback');
    });
    Route::middleware(['auth','can:seeker.view'])->group(function () {
        Route::get('/',[SeekerDashboardController::class,'index'])->name('dashboard');
        Route::get('/privacy',[SeekerPrivacyController::class,'index'])->name('privacy');
        Route::get('/privacy/export',[SeekerPrivacyController::class,'export'])->middleware('throttle:3,60')->name('privacy.export');
        Route::get('/profile',[SeekerDashboardController::class,'profile'])->name('profile');
        Route::patch('/profile',[SeekerDashboardController::class,'updateProfile'])->name('profile.update');
        Route::post('/saved-jobs/{job}',[SeekerDashboardController::class,'save'])->name('jobs.save');
        Route::delete('/saved-jobs/{job}',[SeekerDashboardController::class,'unsave'])->name('jobs.unsave');
        Route::get('/submitted-applications',[SeekerRecruitmentController::class,'index'])->name('submitted');
        Route::post('/apply/{job}',[SeekerRecruitmentController::class,'store'])->middleware('throttle:10,1')->name('apply');
        Route::get('/applications',[SeekerApplicationsController::class,'index'])->name('applications');
        Route::get('/applications/{job}/history',[SeekerApplicationsController::class,'history'])->name('applications.history');
        Route::post('/applications/{job}',[SeekerDashboardController::class,'track'])->name('jobs.track');
        Route::post('/resume',[SeekerResumeController::class,'store'])->middleware('throttle:10,1')->name('resume.store');
        Route::get('/resume',[SeekerResumeController::class,'download'])->name('resume.download');
        Route::delete('/resume',[SeekerResumeController::class,'destroy'])->name('resume.destroy');
        Route::get('/recommendations',[SeekerDiscoveryController::class,'recommendations'])->name('recommendations');
        Route::get('/alerts',[SeekerDiscoveryController::class,'alerts'])->name('alerts');
        Route::post('/alerts',[SeekerDiscoveryController::class,'storeAlert'])->middleware('throttle:10,1')->name('alerts.store');
        Route::patch('/alerts/{alert}',[SeekerDiscoveryController::class,'updateAlert'])->middleware('throttle:20,1')->name('alerts.update');
        Route::delete('/alerts/{alert}',[SeekerDiscoveryController::class,'deleteAlert'])->name('alerts.delete');
        Route::post('/logout',[SeekerAuthController::class,'logout'])->name('logout');
    });
});

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
