<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Authorization\Models\Role;
use App\Modules\Authorization\Models\Permission;
use App\Modules\Authorization\Policies\RolePolicy;
use App\Modules\Authorization\Policies\PermissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    protected $policies = [
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    public function register(): void
    {
        $this->app->singleton(
            \App\Modules\Authorization\Services\AuthorizationService::class
        );
    }

    public function boot(): void
    {
        $this->registerPolicies();

        // Register gates here if needed
    }
}
