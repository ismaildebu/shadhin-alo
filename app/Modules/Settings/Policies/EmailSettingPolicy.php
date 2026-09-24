<?php

declare(strict_types=1);

namespace App\Modules\Settings\Policies;

use App\Modules\Authentication\Models\User;

class EmailSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    public function test(User $user): bool
    {
        return $user->isAdmin();
    }
}
