<?php

declare(strict_types=1);

namespace App\Modules\Settings\Listeners;

use App\Modules\Settings\Events\SettingUpdated;
use Illuminate\Support\Facades\Cache;

class ClearSettingsCache
{
    public function handle(SettingUpdated $event): void
    {
        Cache::forget('settings.all');
        Cache::forget('settings.system');
        Cache::forget('settings.module.' . $event->setting->module);
    }
}
