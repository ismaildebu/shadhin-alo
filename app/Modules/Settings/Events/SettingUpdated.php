<?php

declare(strict_types=1);

namespace App\Modules\Settings\Events;

use App\Modules\Settings\Models\Setting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Setting $setting)
    {
    }
}
