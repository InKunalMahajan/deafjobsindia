<?php
namespace Tests\Feature;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AdminManagementTest extends TestCase {
    use RefreshDatabase;
    private function user(string $role): User {
        $user=User::factory()->create();$user->role=$role;$user->save();return $user;
    }
    private function employer(): Employer {
        return Employer::create(['name'=>'Example Organisation','slug'=>'example-org']);
    }
    private function jobData(Employer $employer,array $overrides=[]): array {
        return array_merge([
            'employer_id'=>$employer->id,'slug'=>'accessibility-engineer',
            'title'=>'Accessibility Engineer','location'=>'Pune',
            'employment_type'=>'full-time','work_mode'=>'remote','sector'=>'private',
            'summary'=>'Accessible technology','description'=>'Build accessible tools',
            'source_url'=>'https://example.org/notice','apply_url'=>'https://example.org/apply',
            'status'=>'draft',
        ],$overrides);
    }
    public function test_guest_is_redirected_to_login(): void {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/login')->assertOk();
    }
    public function test_login_and_logout(): void {
        $user=$this->user('admin');
        $this->post('/admin/login',['email'=>$user->email,'password'=>'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->post('/admin/logout')->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
    public function test_viewer_cannot_change_records(): void {
        $this->actingAs($this->user('viewer'))->get('/admin')->assertOk();
        $this->post('/admin/employers',['name'=>'No','slug'=>'no'])->assertForbidden();
        $this->post('/admin/jobs',[])->assertForbidden();
        $this->get('/admin/users')->assertForbidden();
    }
    public function test_editor_can_create_draft_but_cannot_publish(): void {
        $employer=$this->employer();
        $this->actingAs($this->user('editor'))
            ->post('/admin/jobs',$this->jobData($employer))->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseHas('job_listings',['slug'=>'accessibility-engineer','status'=>'draft']);
        $this->post('/admin/jobs',$this->jobData($employer,['slug'=>'published-attempt','status'=>'published']))
            ->assertForbidden();
    }
    public function test_admin_can_manage_employers_and_publish_jobs(): void {
        $this->actingAs($this->user('admin'))
            ->post('/admin/employers',['name'=>'New Employer','slug'=>'new-employer'])
            ->assertRedirect(route('admin.employers.index'));
        $employer=Employer::where('slug','new-employer')->firstOrFail();
        $this->post('/admin/jobs',$this->jobData($employer,['status'=>'published']))
            ->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseHas('job_listings',['slug'=>'accessibility-engineer','status'=>'published']);
        $this->get('/jobs/accessibility-engineer')->assertOk();
        $this->get('/admin/users')->assertForbidden();
    }
    public function test_super_admin_can_create_user_but_cannot_delete_self(): void {
        $admin=$this->user('super_admin');
        $this->actingAs($admin)->post('/admin/users',[
            'name'=>'Editor User','email'=>'editor@example.org','role'=>'editor',
            'password'=>'long-password-123','password_confirmation'=>'long-password-123',
        ])->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users',['email'=>'editor@example.org','role'=>'editor']);
        $this->delete(route('admin.users.destroy',$admin))->assertSessionHasErrors('user');
    }
    public function test_invalid_application_url_is_rejected(): void {
        $employer=$this->employer();
        $this->actingAs($this->user('admin'))
            ->post('/admin/jobs',$this->jobData($employer,['apply_url'=>'javascript:alert(1)']))
            ->assertSessionHasErrors('apply_url');
    }
}
