<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ProductionReadinessTest extends TestCase {
 use RefreshDatabase;
 public function test_health_endpoint(): void {$this->get('/up')->assertOk();}
 public function test_public_pages_load(): void {
  foreach(['/','/jobs','/latest','/employers','/about','/contact'] as $url)$this->get($url)->assertOk();
 }
 public function test_private_routes_require_login(): void {
  $this->get('/admin')->assertRedirect('/login');
  $this->get('/admin/jobs')->assertRedirect('/login');
 }
 public function test_security_headers(): void {
  $this->get('/')->assertHeader('X-Frame-Options','DENY')->assertHeader('X-Content-Type-Options','nosniff');
 }
 public function test_robots_and_admin_noindex(): void {
  $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin/');
  $this->get('/admin/login')->assertOk()->assertSee('noindex,nofollow');
 }
}