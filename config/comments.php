<?php

return [
    'moderation' => [
        'auto_approve' => false,
        'require_approval' => true,
        'spam_keywords' => [
            'viagra', 'casino', 'lottery', 'cialis',
        ],
    ],

    'pagination' => [
        'per_page' => 20,
    ],

    'limits' => [
        'min_content_length' => 3,
        'max_content_length' => 5000,
        'comments_per_user_per_day' => 50,
    ],

    'permissions' => [
        'comment.create',
        'comment.edit',
        'comment.delete',
        'comment.approve',
        'comment.reject',
        'comment.flag',
    ],

    'flag_reasons' => [
        'spam' => 'Spam or promotional content',
        'offensive' => 'Offensive or abusive language',
        'irrelevant' => 'Irrelevant to the article',
        'misinformation' => 'Contains misinformation',
        'other' => 'Other reason',
    ],

    'retention' => [
        'deleted_comments_days' => 365,
    ],
];
