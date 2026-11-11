<?php
namespace Tests\Feature;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PublicPagesTest extends TestCase {
 use RefreshDatabase;
 public function test_public_pages_render(): void {
  foreach (['/','/jobs','/latest','/education','/training','/career','/about','/contact','/privacy','/disclaimer','/search'] as $url) $this->get($url)->assertOk();
 }
 public function test_homepage_navigation_search_and_footer(): void {
  $this->get('/')->assertOk()->assertSee('Browse all jobs')->assertSee('Find your next opportunity')->assertSee('Mobile navigation')->assertSee('Legal and information');
 }
 public function test_query_populates_search(): void { $this->get('/search?q=designer')->assertOk()->assertSee('designer'); }
 public function test_homepage_displays_published_articles(): void {
  Article::create(['slug'=>'stage-three-test','title'=>'Stage Three Test Job','description'=>'Vacancy','category'=>'Jobs','body'=>'Details','status'=>'published','published_at'=>now()->subDay()]);
  $this->get('/')->assertSee('Stage Three Test Job');
 }
}