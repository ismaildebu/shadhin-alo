<?php

declare(strict_types=1);

namespace App\Modules\Settings\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Settings\Models\Setting;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('setting.view') || $user->isAdmin();
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->hasPermission('setting.view') || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('setting.create') || $user->isAdmin();
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->hasPermission('setting.update') || $user->isAdmin();
    }

    public function delete(User $user, Setting $setting): bool
    {
        return $user->hasPermission('setting.delete') || $user->isAdmin();
    }
}
