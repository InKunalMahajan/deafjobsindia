<?php
namespace Tests\Feature;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class StageSevenTest extends TestCase {
 use RefreshDatabase;
 public function test_accessibility_and_seo_metadata(): void {
  $this->get('/')->assertOk()->assertSee('Skip to main content')->assertSee('rel="canonical"',false)->assertSee('property="og:title"',false);
 }
 public function test_security_headers(): void {
  $this->get('/')->assertHeader('X-Content-Type-Options','nosniff')->assertHeader('X-Frame-Options','DENY');
 }
 public function test_sitemap_does_not_expose_drafts(): void {
  Article::create(['slug'=>'visible','title'=>'Visible','description'=>'Summary','category'=>'Career','body'=>'Body','status'=>'published','is_demo'=>false,'published_at'=>now()->subDay()]);
  Article::create(['slug'=>'secret','title'=>'Secret','description'=>'Summary','category'=>'Career','body'=>'Body','status'=>'draft']);
  $this->get('/sitemap.xml')->assertOk()->assertSee('/news/visible')->assertDontSee('/news/secret');
 }
 public function test_robots_disallows_internal_routes(): void {
  $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin/')->assertSee('Disallow: /search');
 }
 public function test_admin_stays_private(): void {$this->get('/admin')->assertRedirect('/login');}
}