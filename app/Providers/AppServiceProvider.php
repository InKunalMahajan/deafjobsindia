<?php
namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        Gate::before(fn (User $user,string $ability) => in_array($ability,['seeker.view','employer.view'],true) ? $user->role === ($ability === 'seeker.view' ? 'job_seeker' : 'employer') : ($user->isSuperAdmin() ? true : null));
        Gate::define('employer.view',fn (User $user) => $user->role==='employer');
        Gate::define('seeker.view',fn (User $user) => $user->role==='job_seeker');
        Gate::define('admin.view',fn (User $user) => in_array($user->role,['admin','editor','viewer'],true));
        Gate::define('employers.manage',fn (User $user) => $user->role==='admin');
        Gate::define('jobs.edit',fn (User $user) => in_array($user->role,['admin','editor'],true));
        Gate::define('articles.edit',fn (User $user) => in_array($user->role,['admin','editor'],true));
        Gate::define('articles.publish',fn (User $user) => $user->role==='admin');
        Gate::define('applications.review',fn (User $user) => $user->role==='admin');
        Gate::define('jobs.publish',fn (User $user) => $user->role==='admin');
        Gate::define('users.manage',fn (User $user) => false);
    }
}
