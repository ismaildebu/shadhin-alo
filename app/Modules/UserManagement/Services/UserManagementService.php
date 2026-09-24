<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Services;

use App\Modules\Authentication\Models\User;
use App\Modules\UserManagement\Models\UserProfile;
use App\Modules\UserManagement\Models\UserPreference;
use App\Modules\UserManagement\Models\UserNotificationPreference;

class UserManagementService
{
    public function createUserProfile(User $user, array $data): UserProfile
    {
        $data['user_id'] = $user->id;
        return UserProfile::create($data);
    }

    public function updateUserProfile(User $user, array $data): UserProfile
    {
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
        $profile->update($data);
        $profile->updateLastProfileUpdate();
        return $profile;
    }

    public function getUserProfile(User $user): UserProfile
    {
        return UserProfile::firstOrCreate(['user_id' => $user->id]);
    }

    public function createUserPreference(User $user, array $data = []): UserPreference
    {
        $data['user_id'] = $user->id;
        return UserPreference::create($data);
    }

    public function updateUserPreference(User $user, array $data): UserPreference
    {
        $preference = UserPreference::firstOrCreate(['user_id' => $user->id]);
        $preference->update($data);
        return $preference;
    }

    public function getUserPreference(User $user): UserPreference
    {
        return UserPreference::firstOrCreate(['user_id' => $user->id]);
    }

    public function updateNotificationPreference(User $user, string $type, array $data): UserNotificationPreference
    {
        $data['user_id'] = $user->id;
        $data['notification_type'] = $type;

        return UserNotificationPreference::updateOrCreate(
            ['user_id' => $user->id, 'notification_type' => $type],
            $data
        );
    }

    public function getNotificationPreference(User $user, string $type): ?UserNotificationPreference
    {
        return UserNotificationPreference::where('user_id', $user->id)
            ->where('notification_type', $type)
            ->first();
    }

    public function seedDefaultPreferences(User $user): void
    {
        // Create default preference
        UserPreference::firstOrCreate(['user_id' => $user->id]);

        // Create default notification preferences
        $notificationTypes = [
            'article_published',
            'article_commented',
            'user_followed',
            'newsletter',
            'system_updates',
        ];

        foreach ($notificationTypes as $type) {
            UserNotificationPreference::firstOrCreate(
                ['user_id' => $user->id, 'notification_type' => $type],
                [
                    'email_enabled' => true,
                    'push_enabled' => true,
                    'in_app_enabled' => true,
                    'frequency' => 'instant',
                    'timezone' => 'UTC',
                ]
            );
        }
    }
}
