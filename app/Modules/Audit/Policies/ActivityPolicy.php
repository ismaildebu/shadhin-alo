<?php

declare(strict_types=1);

namespace App\Modules\Audit\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Audit\Models\Activity;

class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('activity.view') || $user->isAdmin();
    }

    public function view(User $user, Activity $activity): bool
    {
        return $user->hasPermission('activity.view') || $user->isAdmin();
    }

    public function export(User $user): bool
    {
        return $user->hasPermission('activity.export') || $user->isAdmin();
    }
}
