<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuditServiceProvider;
use App\Providers\SettingsServiceProvider;

return [
    AppServiceProvider::class,
    SettingsServiceProvider::class,
    AuditServiceProvider::class,
];
