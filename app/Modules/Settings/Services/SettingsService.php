<?php

declare(strict_types=1);

namespace App\Modules\Settings\Services;

use App\Modules\Settings\Models\Setting;
use App\Modules\Settings\Models\EmailSetting;
use App\Modules\Settings\Models\FeatureFlag;

class SettingsService
{
    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public function set(string $key, $value, ?string $type = null, ?string $module = null): Setting
    {
        return Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type ?? 'string',
                'module' => $module ?? 'system',
            ]
        );
    }

    public function getByModule(string $module): array
    {
        return Setting::getByModule($module);
    }

    public function getSystemSettings(): array
    {
        return Setting::where('module', 'system')
            ->where('is_public', true)
            ->pluck('value', 'key')
            ->toArray();
    }

    public function getEmailSettings(): EmailSetting
    {
        return EmailSetting::first() ?? new EmailSetting();
    }

    public function isFeatureEnabled(string $slug, ?int $userId = null, ?string $role = null): bool
    {
        $flag = FeatureFlag::where('slug', $slug)->first();

        if (!$flag) {
            return false;
        }

        if ($userId) {
            return $flag->isEnabledForUser($userId);
        }

        if ($role) {
            return $flag->isEnabledForRole($role);
        }

        return $flag->is_enabled;
    }

    public function seedDefaultSettings(): void
    {
        $defaults = [
            ['key' => 'app_name', 'value' => 'স্বাধীন আলো', 'type' => 'string', 'module' => 'system'],
            ['key' => 'app_description', 'value' => 'একটি সম্পূর্ণ অনলাইন সংবাদপত্র প্ল্যাটফর্ম', 'type' => 'string', 'module' => 'system'],
            ['key' => 'app_url', 'value' => env('APP_URL'), 'type' => 'string', 'module' => 'system'],
            ['key' => 'contact_email', 'value' => 'contact@shadhin-alo.com', 'type' => 'string', 'module' => 'system'],
            ['key' => 'support_email', 'value' => 'support@shadhin-alo.com', 'type' => 'string', 'module' => 'system'],
            ['key' => 'items_per_page', 'value' => '15', 'type' => 'integer', 'module' => 'system'],
            ['key' => 'cache_ttl', 'value' => '3600', 'type' => 'integer', 'module' => 'system'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'module' => 'security'],
            ['key' => 'login_attempt_timeout', 'value' => '15', 'type' => 'integer', 'module' => 'security'],
        ];

        foreach ($defaults as $default) {
            Setting::firstOrCreate(['key' => $default['key']], $default);
        }
    }
}
