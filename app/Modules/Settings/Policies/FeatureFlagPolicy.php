<?php

declare(strict_types=1);

namespace App\Modules\Settings\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Settings\Models\FeatureFlag;

class FeatureFlagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('feature_flag.view') || $user->isAdmin();
    }

    public function view(User $user, FeatureFlag $flag): bool
    {
        return $user->hasPermission('feature_flag.view') || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('feature_flag.create') || $user->isAdmin();
    }

    public function update(User $user, FeatureFlag $flag): bool
    {
        return $user->hasPermission('feature_flag.update') || $user->isAdmin();
    }

    public function delete(User $user, FeatureFlag $flag): bool
    {
        return $user->hasPermission('feature_flag.delete') || $user->isAdmin();
    }
}
