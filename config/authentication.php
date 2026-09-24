<?php

/**
 * Authentication Module Configuration
 * 
 * এই কনফিগারেশন ফাইল সমস্ত অথেন্টিকেশন সেটিংস পরিচালনা করে।
 * ভবিষ্যতে পরিবর্তন এখানেই করতে হবে।
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    */

    // ব্যবহারকারী মডেল ক্লাস
    'user_model' => \App\Modules\Authentication\Models\User::class,

    // প্যাসওয়ার্ড রিসেট টোকেন মেয়াদ (মিনিটে)
    'password_reset_token_lifetime' => env('PASSWORD_RESET_TOKEN_LIFETIME', 60),

    // ইমেইল যাচাইকরণ টোকেন মেয়াদ (দিনে)
    'email_verification_token_lifetime' => env('EMAIL_VERIFICATION_TOKEN_LIFETIME', 7),

    // লগইন প্রচেষ্টা সীমা
    'max_login_attempts' => env('MAX_LOGIN_ATTEMPTS', 5),

    // লগইন প্রচেষ্টা লক সময় (মিনিটে)
    'login_attempt_lock_time' => env('LOGIN_ATTEMPT_LOCK_TIME', 15),

    // API টোকেন মেয়াদ (দিনে)
    'api_token_lifetime' => env('API_TOKEN_LIFETIME', 365),

    // 2FA সক্ষম করা হয়েছে
    'two_factor_enabled' => env('TWO_FACTOR_ENABLED', false),

    // পাসওয়ার্ড নিয়ম
    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 8),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_special_chars' => env('PASSWORD_REQUIRE_SPECIAL_CHARS', true),
        'special_chars' => '!@#$%^&*()_+-=[]{}|;:,.<>?',
    ],

    // ইমেইল যাচাইকরণ আবশ্যক
    'email_verification_required' => env('EMAIL_VERIFICATION_REQUIRED', true),

    // সোশ্যাল লগইন সেটিংস (ভবিষ্যত)
    'social_login' => [
        'google_enabled' => env('GOOGLE_LOGIN_ENABLED', false),
        'facebook_enabled' => env('FACEBOOK_LOGIN_ENABLED', false),
        'github_enabled' => env('GITHUB_LOGIN_ENABLED', false),
    ],

    // সেশন টাইমআউট (মিনিটে)
    'session_timeout' => env('SESSION_TIMEOUT', 120),

    // নতুন ডিভাইসে সতর্কতা পাঠান
    'notify_on_new_device' => env('NOTIFY_ON_NEW_DEVICE', true),

    // IP পরিবর্তন সনাক্তকরণ
    'detect_ip_changes' => env('DETECT_IP_CHANGES', true),

    // GDPR সম্মতি
    'gdpr_compliant' => env('GDPR_COMPLIANT', true),

    // অ্যাকাউন্ট লক-আউট অক্ষম করা (ডেভেলপমেন্টের জন্য)
    'disable_lockout' => env('APP_DEBUG', false),
];