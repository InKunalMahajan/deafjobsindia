<?php

namespace Tests\Feature;

use App\Livewire\ArticleSearch;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StageEightArticleTest extends TestCase
{
    use RefreshDatabase;

    private function article(string $slug, array $extra = []): Article
    {
        return Article::create(array_merge([
            'slug' => $slug,
            'title' => 'Article '.$slug,
            'description' => 'Description '.$slug,
            'category' => 'Career',
            'body' => 'Article details',
            'status' => 'published',
            'is_demo' => false,
            'published_at' => now()->subDay(),
        ], $extra));
    }

    private function admin(string $role = 'admin'): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_public_news_excludes_drafts_demo_and_future_articles(): void
    {
        $this->article('public');
        $this->article('draft', ['status' => 'draft']);
        $this->article('demo', ['is_demo' => true]);
        $this->article('future', ['published_at' => now()->addDay()]);

        $this->get('/latest')->assertOk()->assertSee('Article public')
            ->assertDontSee('Article draft')->assertDontSee('Article demo')
            ->assertDontSee('Article future');

        $this->get('/news/draft')->assertNotFound();
        $this->get('/news/demo')->assertNotFound();
        $this->get('/news/future')->assertNotFound();
    }

    public function test_search_filters_category_and_does_not_expose_drafts(): void
    {
        $this->article('career-visible');
        $this->article('education-visible', ['category' => 'Education']);
        $this->article('career-hidden', ['status' => 'draft']);

        Livewire::test(ArticleSearch::class)->set('category', 'Career')
            ->assertSee('Article career-visible')
            ->assertDontSee('Article education-visible')
            ->assertDontSee('Article career-hidden');
    }

    public function test_search_reset_clears_filters(): void
    {
        $this->article('career-visible');
        Livewire::test(ArticleSearch::class)->set('category', 'Education')
            ->assertDontSee('Article career-visible')
            ->call('clearFilters')->assertSee('Article career-visible');
    }

    public function test_editor_cannot_publish_article(): void
    {
        $this->actingAs($this->admin('editor'))
            ->post(route('admin.articles.store'), [
                'title' => 'Forbidden publication',
                'slug' => 'forbidden-publication',
                'description' => 'A description',
                'category' => 'Career',
                'body' => 'Article content',
                'status' => 'published',
            ])->assertForbidden();

        $this->assertDatabaseMissing('articles', ['slug' => 'forbidden-publication']);
    }

    public function test_editor_cannot_modify_published_article(): void
    {
        $article = $this->article('published');
        $this->actingAs($this->admin('editor'))
            ->put(route('admin.articles.update', $article), [
                'title' => 'Unauthorized change',
                'slug' => $article->slug,
                'description' => $article->description,
                'category' => $article->category,
                'body' => $article->body,
                'status' => 'draft',
            ])->assertForbidden();
        $this->assertDatabaseHas('articles', ['id' => $article->id, 'status' => 'published']);
    }

    public function test_admin_can_unpublish_and_republish_without_losing_content(): void
    {
        $article = $this->article('reusable');
        $admin = $this->admin();
        $payload = [
            'title' => $article->title,
            'slug' => $article->slug,
            'description' => $article->description,
            'category' => $article->category,
            'body' => $article->body,
        ];

        $this->actingAs($admin)->put(route('admin.articles.update', $article),
            array_merge($payload, ['status' => 'draft']))
            ->assertRedirect(route('admin.articles.index'));
        $this->get('/news/reusable')->assertNotFound();

        $this->put(route('admin.articles.update', $article),
            array_merge($payload, ['status' => 'published']))
            ->assertRedirect(route('admin.articles.index'));
        $this->get('/news/reusable')->assertOk()->assertSee('Article reusable');
    }

    public function test_legacy_category_is_preserved_during_article_edit(): void
    {
        $article = $this->article('legacy', ['category' => 'Local Announcements']);
        $this->actingAs($this->admin())->put(route('admin.articles.update', $article), [
            'title' => 'Updated legacy article',
            'slug' => 'legacy',
            'description' => $article->description,
            'category' => 'Local Announcements',
            'body' => $article->body,
            'status' => 'published',
        ])->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'category' => 'Local Announcements',
            'title' => 'Updated legacy article',
        ]);
    }
}
