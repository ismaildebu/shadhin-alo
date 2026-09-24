<?php

declare(strict_types=1);

namespace App\Modules\Audit\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Audit\Models\PermissionAudit;

class PermissionAuditPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('permission_audit.view') || $user->isAdmin();
    }

    public function view(User $user, PermissionAudit $audit): bool
    {
        return $user->hasPermission('permission_audit.view') || $user->isAdmin();
    }
}
