<?php
namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        Gate::before(fn (User $user,string $ability) => $user->isSuperAdmin() ? true : null);
        Gate::define('admin.view',fn (User $user) => in_array($user->role,['admin','editor','viewer'],true));
        Gate::define('employers.manage',fn (User $user) => $user->role==='admin');
        Gate::define('jobs.edit',fn (User $user) => in_array($user->role,['admin','editor'],true));
        Gate::define('jobs.publish',fn (User $user) => $user->role==='admin');
        Gate::define('users.manage',fn (User $user) => false);
    }
}
