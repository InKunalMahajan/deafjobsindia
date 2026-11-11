<?php
namespace Tests\Feature;
use App\Models\Employer;
use App\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class StageNineTest extends TestCase {
 use RefreshDatabase;
 public function test_homepage_job_search_targets_jobs_not_articles(): void {
  $this->get('/')->assertOk()->assertSee('action="'.route('jobs.index').'"',false)
   ->assertSee('name="q"',false)->assertSee('Find your next opportunity');
 }
 public function test_homepage_has_employer_navigation_and_application_guidance(): void {
  $this->get('/')->assertOk()->assertSee(route('employers.index'))
   ->assertSee('Complete your application on the official employer website.');
 }
 public function test_job_query_is_passed_to_livewire(): void {
  $this->get('/jobs?q=Accountant')->assertOk()->assertSee('Accountant');
 }
 public function test_news_search_is_clearly_separate(): void {
  $this->get('/search?q=Accountant')->assertOk()
   ->assertSee('Search news and resources')->assertSee('Search jobs instead');
 }
 public function test_homepage_does_not_show_unpublished_jobs(): void {
  $employer=Employer::create(['name'=>'Example Employer','slug'=>'example-employer']);
  JobListing::create(['employer_id'=>$employer->id,'slug'=>'private-job','title'=>'Private Draft Job',
   'location'=>'Pune','employment_type'=>'full-time','work_mode'=>'remote','sector'=>'private',
   'summary'=>'Internal only','description'=>'Not published','source_url'=>'https://example.org/source',
   'apply_url'=>'https://example.org/apply','status'=>'draft']);
  $this->get('/')->assertOk()->assertDontSee('Private Draft Job');
 }
}