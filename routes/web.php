<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/latest', [NewsController::class, 'index'])->name('latest');
Route::view('/jobs', 'pages.jobs')->name('jobs.index');
Route::view('/search', 'pages.search')->name('search');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/{section}', function (string $section, NewsController $news) {
    $categories = [
        'education' => 'Education',
        'training' => 'Training',
        'career' => 'Career',
    ];

    abort_unless(array_key_exists($section, $categories), 404);

    return $news->index($categories[$section]);
})->whereIn('section', ['education', 'training', 'career'])->name('section.show');

foreach ([
    'schemes' => 'Government Schemes',
    'job-fairs' => 'Job Fairs',
    'community' => 'Community',
    'technology' => 'Technology',
    'government-jobs' => 'Jobs',
    'deaf-jobs' => 'Jobs',
    'pwd-jobs' => 'Jobs',
    'maharashtra-jobs' => 'Jobs',
    'mpsc-jobs' => 'Jobs',
    'mumbai-jobs' => 'Jobs',
    'pune-jobs' => 'Jobs',
    'railway-jobs' => 'Jobs',
    'ssc-jobs' => 'Jobs',
    'upsc-jobs' => 'Jobs',
] as $path => $category) {
    Route::get('/'.$path, fn (NewsController $news) => $news->index($category));
}

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/disclaimer', 'pages.disclaimer')->name('disclaimer');
Route::view('/advertise', 'pages.advertise')->name('advertise');

Route::get('/rss.xml', function () {
    return response()->view('feeds.rss', [
        'articles' => Article::published()->latest('published_at')->limit(25)->get(),
    ])->header('Content-Type', 'application/rss+xml; charset=UTF-8');
})->name('rss');

Route::get('/design-system', function () {
    abort_unless(app()->environment('local', 'testing'), 404);

    return view('pages.design-system');
})->name('design-system');
