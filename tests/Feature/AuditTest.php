<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Modules\Audit\Models\Activity;
use App\Modules\Audit\Models\LoginAudit;
use App\Modules\Audit\Models\PermissionAudit;
use App\Modules\Audit\Services\AuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuditTest extends TestCase
{
    public function test_audit_tables_exist(): void
    {
        $this->assertTrue(
            DB::getSchemaBuilder()->hasTable('activities')
        );

        $this->assertTrue(
            DB::getSchemaBuilder()->hasTable('login_audits')
        );

        $this->assertTrue(
            DB::getSchemaBuilder()->hasTable('permission_audits')
        );
    }

    public function test_audit_service_is_registered(): void
    {
        $service = app(AuditService::class);

        $this->assertInstanceOf(AuditService::class, $service);
    }

    public function test_audit_policies_are_registered(): void
    {
        $this->assertNotNull(
            Gate::getPolicyFor(Activity::class)
        );

        $this->assertNotNull(
            Gate::getPolicyFor(LoginAudit::class)
        );

        $this->assertNotNull(
            Gate::getPolicyFor(PermissionAudit::class)
        );
    }

    public function test_audit_routes_are_registered(): void
    {
        $routes = collect(app('router')->getRoutes());

        $this->assertTrue(
            $routes->contains(
                fn ($route) => $route->uri() === 'audit/activities'
            )
        );

        $this->assertTrue(
            $routes->contains(
                fn ($route) => $route->uri() === 'audit/login-audits'
            )
        );

        $this->assertTrue(
            $routes->contains(
                fn ($route) => $route->uri() === 'audit/permission-audits'
            )
        );
    }

    public function test_audit_routes_require_authentication(): void
    {
        $routes = collect(app('router')->getRoutes());

        $auditRoutes = $routes->filter(
            fn ($route) => str_starts_with($route->uri(), 'audit/')
        );

        $this->assertGreaterThan(0, $auditRoutes->count());

        foreach ($auditRoutes as $route) {
            $this->assertContains(
                'auth:sanctum',
                $route->middleware()
            );
        }
    }

    public function test_activity_record_can_be_created(): void
    {
        DB::table('activities')->insert([
            'action' => 'viewed',
            'description' => 'Audit test activity',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('activities', [
            'action' => 'viewed',
            'description' => 'Audit test activity',
        ]);
    }

    public function test_login_audit_record_can_be_created(): void
    {
        DB::table('login_audits')->insert([
            'action' => 'login',
            'email' => 'audit-test@example.com',
            'is_successful' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('login_audits', [
            'action' => 'login',
            'email' => 'audit-test@example.com',
            'is_successful' => true,
        ]);
    }

    public function test_permission_audit_record_can_be_created(): void
    {
        DB::table('permission_audits')->insert([
            'permission' => 'activity.view',
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('permission_audits', [
            'permission' => 'activity.view',
            'is_allowed' => true,
        ]);
    }

    public function test_audit_is_enabled_by_default(): void
    {
        $this->assertTrue(
            (bool) config('audit.enabled')
        );
    }
}
