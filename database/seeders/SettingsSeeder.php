<?php

namespace Database\Seeders;

use App\Modules\Settings\Models\Setting;
use App\Modules\Settings\Models\FeatureFlag;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::truncate();
        FeatureFlag::truncate();

        // System Settings
        $systemSettings = [
            ['key' => 'app_name', 'value' => 'স্বাধীন আলো', 'type' => 'string', 'description' => 'Application name', 'module' => 'system', 'is_public' => true],
            ['key' => 'app_description', 'value' => 'একটি সম্পূর্ণ অনলাইন সংবাদপত্র প্ল্যাটফর্ম', 'type' => 'string', 'description' => 'Application description', 'module' => 'system', 'is_public' => true],
            ['key' => 'app_logo_url', 'value' => '/logos/shadhin-alo-logo.svg', 'type' => 'string', 'description' => 'Application logo URL', 'module' => 'system', 'is_public' => true],
            ['key' => 'contact_email', 'value' => 'contact@shadhin-alo.com', 'type' => 'string', 'description' => 'Contact email', 'module' => 'system', 'is_public' => true],
            ['key' => 'support_email', 'value' => 'support@shadhin-alo.com', 'type' => 'string', 'description' => 'Support email', 'module' => 'system', 'is_public' => true],
            ['key' => 'items_per_page', 'value' => '15', 'type' => 'integer', 'description' => 'Default pagination size', 'module' => 'system', 'is_public' => true],
            ['key' => 'cache_ttl', 'value' => '3600', 'type' => 'integer', 'description' => 'Cache TTL in seconds', 'module' => 'system', 'is_public' => false],
        ];

        Setting::insert($systemSettings);

        // Security Settings
        $securitySettings = [
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'description' => 'Max login attempts', 'module' => 'security', 'is_public' => true],
            ['key' => 'login_attempt_timeout', 'value' => '15', 'type' => 'integer', 'description' => 'Login timeout in minutes', 'module' => 'security', 'is_public' => true],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'integer', 'description' => 'Session timeout in minutes', 'module' => 'security', 'is_public' => false],
            ['key' => 'require_email_verification', 'value' => 'true', 'type' => 'boolean', 'description' => 'Require email verification', 'module' => 'security', 'is_public' => true],
            ['key' => 'enable_two_factor', 'value' => 'false', 'type' => 'boolean', 'description' => 'Enable 2FA', 'module' => 'security', 'is_public' => true],
        ];

        Setting::insert($securitySettings);

        // Feature Flags
        $flags = [
            ['name' => 'Advanced Analytics', 'slug' => 'advanced_analytics', 'description' => 'Advanced analytics dashboard', 'is_enabled' => true, 'rollout_percentage' => 100],
            ['name' => 'Social Sharing', 'slug' => 'social_sharing', 'description' => 'Social media sharing features', 'is_enabled' => true, 'rollout_percentage' => 100],
            ['name' => 'Paywalls', 'slug' => 'paywalls', 'description' => 'Premium content paywalls', 'is_enabled' => false, 'rollout_percentage' => 0],
            ['name' => 'Dark Mode', 'slug' => 'dark_mode', 'description' => 'Dark mode UI', 'is_enabled' => true, 'rollout_percentage' => 100],
            ['name' => 'Recommendations Engine', 'slug' => 'recommendations_engine', 'description' => 'AI-powered content recommendations', 'is_enabled' => false, 'rollout_percentage' => 50],
        ];

        foreach ($flags as $flag) {
            FeatureFlag::create($flag);
        }
    }
}
