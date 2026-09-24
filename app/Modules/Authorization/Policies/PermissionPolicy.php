<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Authorization\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('permission.view');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermission('permission.view');
    }
}
