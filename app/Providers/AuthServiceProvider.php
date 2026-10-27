<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-radar', fn (User $user) => (int) $user->perfil === User::ROLE_SYSTEM_ADMIN);
        Gate::define('access-client-dashboard', fn (User $user) => (int) $user->perfil === User::ROLE_CLIENT_USER);
        Gate::define('access-technician-dashboard', fn (User $user) => (int) $user->perfil === User::ROLE_TECHNICIAN);
        Gate::define('access-ticket-area', fn (User $user) => in_array((int) $user->perfil, [User::ROLE_SYSTEM_ADMIN, User::ROLE_CLIENT_USER], true));
        Gate::define('access-technician-queue', fn (User $user) => in_array((int) $user->perfil, [User::ROLE_SYSTEM_ADMIN, User::ROLE_TECHNICIAN], true));
    }
}
