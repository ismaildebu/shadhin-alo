<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Traits;

use App\Modules\UserManagement\Models\UserProfile;
use App\Modules\UserManagement\Models\UserPreference;
use App\Modules\UserManagement\Models\UserNotificationPreference;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasProfileAndPreferences
{
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class, 'user_id');
    }

    public function notificationPreferences(): HasMany
    {
        return $this->hasMany(UserNotificationPreference::class, 'user_id');
    }

    public function getFullName(): string
    {
        return $this->profile?->getFullName() ?? '';
    }

    public function getTheme(): string
    {
        return $this->preference?->getTheme() ?? 'light';
    }

    public function getLanguage(): string
    {
        return $this->preference?->getLanguage() ?? 'en';
    }

    public function getTimezone(): string
    {
        return $this->preference?->getTimezone() ?? 'UTC';
    }

    public function canReceiveEmails(): bool
    {
        return $this->preference?->canReceiveEmails() ?? false;
    }

    public function canReceivePushNotifications(): bool
    {
        return $this->preference?->canReceivePushNotifications() ?? false;
    }
}
