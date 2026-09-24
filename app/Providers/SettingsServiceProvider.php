<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Settings\Models\Setting;
use App\Modules\Settings\Models\FeatureFlag;
use App\Modules\Settings\Policies\SettingPolicy;
use App\Modules\Settings\Policies\FeatureFlagPolicy;
use App\Modules\Settings\Policies\EmailSettingPolicy;
use App\Modules\Settings\Services\SettingsService;
use App\Modules\Settings\Events\SettingUpdated;
use App\Modules\Settings\Listeners\ClearSettingsCache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsService::class, function () {
            return new SettingsService();
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishesMigrations([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ]);

        $this->publishes([
            __DIR__ . '/../../config/settings.php' => config_path('settings.php'),
        ], 'settings-config');

        $this->registerPolicies();
        $this->registerEvents();
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Setting::class, SettingPolicy::class);
        Gate::policy(FeatureFlag::class, FeatureFlagPolicy::class);
        Gate::policy(\App\Modules\Settings\Models\EmailSetting::class, EmailSettingPolicy::class);
    }

    protected function registerEvents(): void
    {
        \Event::listen(SettingUpdated::class, ClearSettingsCache::class);
    }
}
