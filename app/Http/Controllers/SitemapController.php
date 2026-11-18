<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => route('home')],
            ['loc' => route('jobs.index')],
            ['loc' => route('latest')],
            ['loc' => route('employers.index')],
            ['loc' => route('about')],
            ['loc' => route('contact')],
        ];

        Article::published()->select(['slug', 'updated_at'])->orderBy('id')->chunkById(500, function ($articles) use (&$urls) {
            foreach ($articles as $article) {
                $urls[] = ['loc' => route('news.show', $article->slug), 'lastmod' => $article->updated_at?->toAtomString()];
            }
        });
        JobListing::published()->open()->select(['id', 'slug', 'updated_at'])->orderBy('id')->chunkById(500, function ($jobs) use (&$urls) {
            foreach ($jobs as $job) {
                $urls[] = ['loc' => route('jobs.show', $job->slug), 'lastmod' => $job->updated_at?->toAtomString()];
            }
        });
        Employer::where('verified', true)->select(['id', 'slug', 'updated_at'])->orderBy('id')->chunkById(500, function ($employers) use (&$urls) {
            foreach ($employers as $employer) {
                $urls[] = ['loc' => route('employers.show', $employer->slug), 'lastmod' => $employer->updated_at?->toAtomString()];
            }
        });

        return response()->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=900');
    }
}
