<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_news_import_is_repeatable_and_excludes_demo(): void
    {
        $this->artisan('news:import')->assertSuccessful();

        $firstCount = Article::count();
        $this->assertGreaterThan(5, $firstCount);
        $this->assertDatabaseMissing('articles', ['slug' => 'deafjobsindia-newsroom-launch']);

        $this->artisan('news:import')->assertSuccessful();
        $this->assertSame($firstCount, Article::count());
    }
}
