<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuditServiceProvider;
use App\Providers\ArticleServiceProvider;
use App\Providers\CommentServiceProvider;
use App\Providers\SettingsServiceProvider;

return [
    AppServiceProvider::class,
    SettingsServiceProvider::class,
    AuditServiceProvider::class,
    ArticleServiceProvider::class,
    CommentServiceProvider::class,
];




