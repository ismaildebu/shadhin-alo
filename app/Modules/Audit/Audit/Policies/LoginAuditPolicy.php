<?php

declare(strict_types=1);

namespace App\Modules\Audit\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Audit\Models\LoginAudit;

class LoginAuditPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('login_audit.view') || $user->isAdmin();
    }

    public function view(User $user, LoginAudit $audit): bool
    {
        return $user->hasPermission('login_audit.view') || $user->isAdmin();
    }
}
