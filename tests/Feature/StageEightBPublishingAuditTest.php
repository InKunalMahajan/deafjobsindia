<?php

namespace Tests\Feature;

use App\Livewire\ArticleSearch;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StageEightBPublishingAuditTest extends TestCase
{
    use RefreshDatabase;

    private function article(string $slug, array $attributes = []): Article
    {
        return Article::create(array_merge([
            'slug' => $slug,
            'title' => 'Notice '.$slug,
            'description' => 'Notice description',
            'category' => 'Community',
            'body' => 'Content with **Markdown**.',
            'status' => 'published',
            'is_demo' => false,
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_search_lists_custom_categories_only_when_published(): void
    {
        $this->article('custom', ['category' => 'Local Announcements']);
        $this->article('hidden', ['category' => 'Secret Category', 'status' => 'draft']);

        Livewire::test(ArticleSearch::class)
            ->assertSee('Local Announcements')
            ->assertDontSee('Secret Category')
            ->set('category', 'Local Announcements')
            ->assertSee('Notice custom')
            ->assertDontSee('Notice hidden');
    }

    public function test_admin_edit_keeps_future_publication_date(): void
    {
        $scheduled = now()->addDays(3)->startOfMinute();
        $article = $this->article('scheduled', ['published_at' => $scheduled]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.articles.update', $article), [
            'title' => 'Edited scheduled notice',
            'slug' => 'scheduled',
            'description' => 'Updated description',
            'category' => 'Community',
            'body' => 'Updated content',
            'status' => 'published',
        ])->assertRedirect(route('admin.articles.index'));

        $this->assertEquals($scheduled->timestamp, $article->fresh()->published_at->timestamp);
        $this->get('/news/scheduled')->assertNotFound();
    }

    public function test_article_markdown_is_rendered_without_raw_html(): void
    {
        $this->article('markdown', [
            'body' => '**Bold text** <script>alert(1)</script>',
        ]);
        $this->get('/news/markdown')->assertOk()
            ->assertSee('<strong>Bold text</strong>', false)
            ->assertDontSee('<script>', false);
    }

    public function test_public_pages_hide_demo_future_and_draft_articles(): void
    {
        $this->article('visible');
        $this->article('demo', ['is_demo' => true]);
        $this->article('future', ['published_at' => now()->addDay()]);
        $this->article('draft', ['status' => 'draft']);

        $this->get('/latest')->assertOk()->assertSee('Notice visible')
            ->assertDontSee('Notice demo')
            ->assertDontSee('Notice future')
            ->assertDontSee('Notice draft');
    }

    public function test_editor_cannot_publish_or_delete_published_article(): void
    {
        $article = $this->article('protected');
        $this->actingAs(User::factory()->create(['role' => 'editor']))
            ->delete(route('admin.articles.destroy', $article))
            ->assertForbidden();
        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }
}
