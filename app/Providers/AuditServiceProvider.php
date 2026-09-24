<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Audit\Models\Activity;
use App\Modules\Audit\Models\LoginAudit;
use App\Modules\Audit\Models\PermissionAudit;
use App\Modules\Audit\Policies\ActivityPolicy;
use App\Modules\Audit\Policies\LoginAuditPolicy;
use App\Modules\Audit\Policies\PermissionAuditPolicy;
use App\Modules\Audit\Services\AuditService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuditService::class, function () {
            return new AuditService();
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../config/audit.php' => config_path('audit.php'),
        ], 'audit-config');

        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::policy(LoginAudit::class, LoginAuditPolicy::class);
        Gate::policy(PermissionAudit::class, PermissionAuditPolicy::class);
    }
}
