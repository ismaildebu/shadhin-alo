<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings Configuration
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'enabled' => true,
        'ttl' => env('SETTINGS_CACHE_TTL', 3600),
    ],

    'default_values' => [
        'items_per_page' => 15,
        'cache_ttl' => 3600,
        'max_login_attempts' => 5,
        'login_attempt_timeout' => 15,
    ],

    'modules' => [
        'system',
        'security',
        'email',
        'api',
        'content',
    ],

    'types' => [
        'string',
        'integer',
        'boolean',
        'array',
        'json',
    ],

    'mail_drivers' => [
        'smtp',
        'sendmail',
        'mailgun',
        'postmark',
        'sendgrid',
        'ses',
    ],
];
