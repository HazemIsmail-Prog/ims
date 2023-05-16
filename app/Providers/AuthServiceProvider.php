<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        if (Schema::hasTable('permissions')) {
            $permissions = Permission::pluck('id', 'name');
            foreach ($permissions as $Permission_name => $permission_id) {
                Gate::define($Permission_name, function ($user) use ($permission_id) {
                    return $user->hasPermission($permission_id);
                });
            }
        }
    }
}
