<?php
namespace Tests\Feature;
use App\Models\Article;
use App\Models\Employer;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class StageSixTest extends TestCase {
 use RefreshDatabase;
 private function actor(string $role): User {$u=User::factory()->create();$u->role=$role;$u->save();return $u;}
 private function article(array $overrides=[]): array {
  return array_merge(['title'=>'Career Advice','slug'=>'career-advice','category'=>'Career',
   'description'=>'Career guidance for job seekers','body'=>'Helpful guidance',
   'source_url'=>'https://example.org/news','status'=>'draft'],$overrides);
 }
 public function test_editor_can_create_draft_article(): void {
  $this->actingAs($this->actor('editor'))->post('/admin/articles',$this->article())
   ->assertRedirect(route('admin.articles.index'));
  $this->assertDatabaseHas('articles',['slug'=>'career-advice','status'=>'draft']);
  $this->get('/news/career-advice')->assertNotFound();
 }
 public function test_editor_cannot_publish_article(): void {
  $this->actingAs($this->actor('editor'))->post('/admin/articles',$this->article(['status'=>'published']))->assertForbidden();
 }
 public function test_admin_can_publish_article(): void {
  $this->actingAs($this->actor('admin'))->post('/admin/articles',$this->article(['status'=>'published']))
   ->assertRedirect(route('admin.articles.index'));
  $this->get('/news/career-advice')->assertOk();
 }
 public function test_only_verified_employer_profiles_are_public(): void {
  Employer::create(['name'=>'Verified Organisation','slug'=>'verified-org','verified'=>true,'location'=>'Pune']);
  Employer::create(['name'=>'Unverified Organisation','slug'=>'unverified-org','verified'=>false]);
  $this->get('/employers')->assertOk()->assertSee('Verified Organisation')->assertDontSee('Unverified Organisation');
  $this->get('/employers/verified-org')->assertOk();
  $this->get('/employers/unverified-org')->assertNotFound();
 }
 public function test_application_review_requires_admin(): void {
  $employer=Employer::create(['name'=>'Sample','slug'=>'sample']);
  $job=JobListing::create([
   'employer_id'=>$employer->id,'slug'=>'sample-job','title'=>'Sample job','location'=>'Pune',
   'employment_type'=>'full-time','work_mode'=>'remote','sector'=>'private',
   'summary'=>'A vacancy','description'=>'Job description',
   'source_url'=>'https://example.org/source','apply_url'=>'https://example.org/apply',
   'status'=>'draft',
  ]);
  $url=route('admin.jobs.application-review',$job);
  $this->actingAs($this->actor('editor'))->patch($url,['application_method'=>'external','application_review_status'=>'verified'])->assertForbidden();
  $this->actingAs($this->actor('admin'))->patch($url,['application_method'=>'external','application_review_status'=>'verified'])->assertRedirect();
  $this->assertDatabaseHas('job_listings',['id'=>$job->id,'application_review_status'=>'verified']);
 }
}
