<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_article_is_visible_at_its_original_url(): void
    {
        $article = Article::create([
            'slug' => 'sample-announcement',
            'title' => 'Verified Example Recruitment',
            'description' => 'Official employment announcement',
            'category' => 'Jobs',
            'body' => 'This job information comes from a source.',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'source_name' => 'Example Office',
            'source_url' => 'https://example.org/jobs',
        ]);

        $this->get(route('news.show', $article->slug))
            ->assertOk()
            ->assertSee('Verified Example Recruitment')
            ->assertSee('Example Office');
    }

    public function test_demo_or_draft_articles_are_hidden(): void
    {
        Article::create([
            'slug' => 'hidden-demo',
            'title' => 'Hidden Demo Job',
            'category' => 'Jobs',
            'body' => 'Draft only',
            'is_demo' => true,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->get('/')->assertDontSee('Hidden Demo Job');
        $this->get('/news/hidden-demo')->assertNotFound();
    }

    public function test_closed_application_is_clearly_labelled(): void
    {
        Article::create([
            'slug' => 'closed-recruitment',
            'title' => 'Closed Recruitment',
            'category' => 'Jobs',
            'body' => 'The deadline has expired.',
            'status' => 'published',
            'published_at' => now()->subMonth(),
            'deadline' => now()->subDays(3),
            'action_url' => 'https://example.org/apply',
        ]);

        $this->get('/news/closed-recruitment')
            ->assertOk()
            ->assertSee('Closed')
            ->assertDontSee('Visit official application page');
    }
}
