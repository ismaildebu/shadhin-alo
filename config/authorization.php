<?php

return [
    'default_guard' => env('AUTH_GUARD', 'web'),

    'roles' => [
        'admin' => [
            'name' => 'Administrator',
            'description' => 'Full system access',
            'is_system' => true,
        ],
        'editor_in_chief' => [
            'name' => 'Editor in Chief',
            'description' => 'Can manage all content',
            'is_system' => true,
        ],
        'editor' => [
            'name' => 'Editor',
            'description' => 'Can edit and publish articles',
            'is_system' => true,
        ],
        'author' => [
            'name' => 'Author',
            'description' => 'Can write articles',
            'is_system' => true,
        ],
        'contributor' => [
            'name' => 'Contributor',
            'description' => 'Can contribute articles',
            'is_system' => true,
        ],
        'subscriber' => [
            'name' => 'Subscriber',
            'description' => 'Can read content',
            'is_system' => true,
        ],
    ],

    'super_admin_roles' => ['admin'],

    'cache_ttl' => env('AUTHORIZATION_CACHE_TTL', 3600),

    'modules' => [
        'authorization',
        'article',
        'user',
        'category',
        'media',
        'comment',
        'settings',
    ],
];
