<?php

namespace Database\Seeders;

use App\Modules\Authorization\Models\Role;
use App\Modules\Authorization\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Role Permissions
            ['name' => 'View Roles', 'slug' => 'role.view', 'module' => 'authorization', 'is_system' => true],
            ['name' => 'Create Role', 'slug' => 'role.create', 'module' => 'authorization', 'is_system' => true],
            ['name' => 'Update Role', 'slug' => 'role.update', 'module' => 'authorization', 'is_system' => true],
            ['name' => 'Delete Role', 'slug' => 'role.delete', 'module' => 'authorization', 'is_system' => true],
            ['name' => 'Manage Permissions', 'slug' => 'role.manage_permissions', 'module' => 'authorization', 'is_system' => true],

            // Permission Permissions
            ['name' => 'View Permissions', 'slug' => 'permission.view', 'module' => 'authorization', 'is_system' => true],

            // Article Permissions
            ['name' => 'View Articles', 'slug' => 'article.view', 'module' => 'article', 'is_system' => false],
            ['name' => 'Create Article', 'slug' => 'article.create', 'module' => 'article', 'is_system' => false],
            ['name' => 'Edit Article', 'slug' => 'article.edit', 'module' => 'article', 'is_system' => false],
            ['name' => 'Publish Article', 'slug' => 'article.publish', 'module' => 'article', 'is_system' => false],
            ['name' => 'Delete Article', 'slug' => 'article.delete', 'module' => 'article', 'is_system' => false],

            // User Permissions
            ['name' => 'View Users', 'slug' => 'user.view', 'module' => 'user', 'is_system' => false],
            ['name' => 'Create User', 'slug' => 'user.create', 'module' => 'user', 'is_system' => false],
            ['name' => 'Edit User', 'slug' => 'user.edit', 'module' => 'user', 'is_system' => false],
            ['name' => 'Delete User', 'slug' => 'user.delete', 'module' => 'user', 'is_system' => false],

            // Category Permissions
            ['name' => 'Manage Categories', 'slug' => 'category.manage', 'module' => 'category', 'is_system' => false],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Create roles
        $roles = [
            'admin' => ['role.view', 'role.create', 'role.update', 'role.delete', 'role.manage_permissions', 'permission.view', 'article.view', 'article.create', 'article.edit', 'article.publish', 'article.delete', 'user.view', 'user.create', 'user.edit', 'user.delete', 'category.manage'],
            'editor_in_chief' => ['article.view', 'article.create', 'article.edit', 'article.publish', 'article.delete', 'category.manage'],
            'editor' => ['article.view', 'article.create', 'article.edit', 'article.publish'],
            'author' => ['article.view', 'article.create', 'article.edit'],
            'contributor' => ['article.view', 'article.create'],
            'reporter' => ['article.view', 'article.create'],
            'subscriber' => ['article.view'],
        ];

        foreach ($roles as $slug => $permissionSlugs) {
            $role = Role::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => ucwords(str_replace('_', ' ', $slug)),
                    'description' => 'System role',
                    'is_system' => true,
                ]
            );

            $permissionIds = Permission::whereIn('slug', $permissionSlugs)
                ->pluck('id')
                ->toArray();

            $role->permissions()->sync($permissionIds);
        }
    }
}
