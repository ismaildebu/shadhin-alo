<?php

declare(strict_types=1);

namespace App\Modules\Settings\Traits;

use App\Modules\Settings\Models\Setting;

trait HasSettings
{
    public static function getSetting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public static function setSetting(string $key, $value, ?string $type = null): Setting
    {
        return Setting::set($key, $value, $type);
    }
}
