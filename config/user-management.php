<?php

return [
    'avatar' => [
        'max_size' => 2048, // KB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    ],

    'cover' => [
        'max_size' => 5120, // KB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    ],

    'profile' => [
        'bio_max_length' => 1000,
        'phone_validation' => true,
    ],

    'preferences' => [
        'default_theme' => 'auto',
        'default_language' => 'en',
        'default_timezone' => 'UTC',
        'items_per_page' => 15,
    ],

    'notification_types' => [
        'article_published',
        'article_commented',
        'user_followed',
        'newsletter',
        'system_updates',
        'security_alerts',
    ],

    'privacy_levels' => [
        'public' => 'Profile is visible to everyone',
        'friends' => 'Profile is visible to friends only',
        'private' => 'Profile is not visible to others',
    ],
];
