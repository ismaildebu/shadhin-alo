<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Audit & Logging Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('AUDIT_ENABLED', true),

    'retention' => [
        'days' => env('AUDIT_RETENTION_DAYS', 90),
        'auto_cleanup' => true,
        'cleanup_schedule' => '0 2 * * *', // 2 AM daily
    ],

    'log_actions' => [
        'created',
        'updated',
        'deleted',
        'viewed',
        'exported',
        'bulk_action',
    ],

    'models_to_audit' => [
        // Add model classes here to enable automatic auditing
        // \App\Modules\Authentication\Models\User::class,
    ],

    'exclude_fields' => [
        'password',
        'email_verified_at',
        'remember_token',
        'api_token',
        'two_factor_secret',
    ],

    'log_request_details' => [
        'method' => true,
        'url' => true,
        'ip_address' => true,
        'user_agent' => true,
        'response_time' => true,
        'status_code' => true,
    ],

    'log_login_details' => [
        'track_location' => false,
        'detect_device' => false,
        'track_failed_attempts' => true,
    ],

    'permissions' => [
        'activity.view',
        'activity.export',
        'login_audit.view',
        'permission_audit.view',
    ],
];
