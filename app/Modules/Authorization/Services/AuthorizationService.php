<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Services;

use App\Modules\Authentication\Models\User;
use App\Modules\Authorization\Models\Role;
use App\Modules\Authorization\Models\Permission;

class AuthorizationService
{
    public function assignRoleToUser(User $user, Role $role): void
    {
        if (!$user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->attach($role->id);
        }
    }

    public function removeRoleFromUser(User $user, Role $role): void
    {
        $user->roles()->detach($role->id);
    }

    public function syncRolesForUser(User $user, array $roleIds): void
    {
        $user->roles()->sync($roleIds);
    }

    public function hasRole(User $user, string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return $user->roles()
            ->whereIn('slug', $roles)
            ->exists();
    }

    public function hasPermission(User $user, string $permission): bool
    {
        // Check if user has direct permission
        $hasDirect = $user->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->exists();

        if ($hasDirect) {
            return true;
        }

        // Check if user is admin
        return $user->roles()
            ->where('slug', 'admin')
            ->exists();
    }

    public function getUserPermissions(User $user): array
    {
        return $user->roles()
            ->with('permissions')
            ->get()
            ->flatMap(function ($role) {
                return $role->permissions->pluck('slug')->toArray();
            })
            ->unique()
            ->toArray();
    }

    public function getUserRoles(User $user): array
    {
        return $user->roles()->pluck('slug')->toArray();
    }

    public function createRole(array $data): Role
    {
        return Role::create($data);
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }

    public function seedDefaultRoles(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access',
                'is_system' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Editor in Chief',
                'slug' => 'editor_in_chief',
                'description' => 'Can manage all content',
                'is_system' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Can edit and publish articles',
                'is_system' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Author',
                'slug' => 'author',
                'description' => 'Can write articles',
                'is_system' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Contributor',
                'slug' => 'contributor',
                'description' => 'Can contribute articles',
                'is_system' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Subscriber',
                'slug' => 'subscriber',
                'description' => 'Can read content',
                'is_system' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
