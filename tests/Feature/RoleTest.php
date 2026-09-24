<?php

declare(strict_types=1);

namespace Tests\Feature\Authorization;

use App\Modules\Authentication\Models\User;
use App\Modules\Authorization\Models\Permission;
use App\Modules\Authorization\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_roles(): void
    {
        $admin = User::factory()
            ->hasAttached(Role::factory(['slug' => 'admin']))
            ->create();

        $response = $this->actingAs($admin)
            ->getJson('/api/v1/roles');

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'name', 'slug', 'description'],
            ],
        ]);
    }

    public function test_can_create_role(): void
    {
        $admin = User::factory()
            ->hasAttached(Role::factory(['slug' => 'admin']))
            ->create();

        $response = $this->actingAs($admin)
            ->postJson('/api/v1/roles', [
                'name' => 'Moderator',
                'slug' => 'moderator',
                'description' => 'Content moderator',
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('roles', [
            'slug' => 'moderator',
        ]);
    }

    public function test_can_update_role(): void
    {
        $admin = User::factory()
            ->hasAttached(Role::factory(['slug' => 'admin']))
            ->create();

        $role = Role::factory([
            'slug' => 'editor',
        ])->create();

        $response = $this->actingAs($admin)
            ->putJson("/api/v1/roles/{$role->id}", [
                'name' => 'Senior Editor',
                'description' => 'Updated description',
            ]);

        $response->assertSuccessful();

        $this->assertEquals(
            'Senior Editor',
            $role->fresh()->name
        );
    }

    public function test_cannot_delete_system_role(): void
    {
        $admin = User::factory()
            ->hasAttached(Role::factory(['slug' => 'admin']))
            ->create();

        $systemRole = Role::factory([
            'slug' => 'system-admin',
            'is_system' => true,
        ])->create();

        $response = $this->actingAs($admin)
            ->deleteJson("/api/v1/roles/{$systemRole->id}");

        $response->assertForbidden();
    }

    public function test_can_assign_permissions_to_role(): void
    {
        $admin = User::factory()
            ->hasAttached(Role::factory(['slug' => 'admin']))
            ->create();

        $role = Role::factory()->create();

        $permissions = collect([
            Permission::create([
                'name' => 'View Users',
                'slug' => 'users.view',
                'description' => 'View users',
                'module' => 'users',
                'is_system' => false,
                'guard_name' => 'web',
            ]),
            Permission::create([
                'name' => 'Create Users',
                'slug' => 'users.create',
                'description' => 'Create users',
                'module' => 'users',
                'is_system' => false,
                'guard_name' => 'web',
            ]),
            Permission::create([
                'name' => 'Update Users',
                'slug' => 'users.update',
                'description' => 'Update users',
                'module' => 'users',
                'is_system' => false,
                'guard_name' => 'web',
            ]),
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/api/v1/roles/{$role->id}/permissions", [
                'permissions' => $permissions->pluck('slug')->toArray(),
            ]);

        $response->assertSuccessful();

        $this->assertEquals(
            3,
            $role->fresh()->permissions()->count()
        );
    }
}