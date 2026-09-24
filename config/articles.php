<?php

return [
    'pagination' => [
        'per_page' => 15,
        'featured_limit' => 10,
    ],

    'statuses' => [
        'draft',
        'pending_review',
        'published',
        'archived',
        'deleted',
    ],

    'excerpt_limit' => 500,
    'min_content_length' => 100,

    'permissions' => [
        'article.view',
        'article.create',
        'article.edit',
        'article.publish',
        'article.delete',
        'article.bulk_action',
    ],

    'reading_time' => [
        'words_per_minute' => 200,
    ],
];
