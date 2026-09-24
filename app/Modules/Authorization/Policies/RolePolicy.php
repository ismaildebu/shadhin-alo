<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Authorization\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('role.view');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('role.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('role.create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('role.update') && !$role->is_system;
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermission('role.delete') && !$role->is_system;
    }

    public function managePermissions(User $user, Role $role): bool
    {
        return $user->hasPermission('role.manage_permissions') && !$role->is_system;
    }
}
