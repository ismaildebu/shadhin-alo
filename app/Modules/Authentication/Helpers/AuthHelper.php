<?php

namespace App\Modules\Authentication\Helpers;

use App\Modules\Authentication\Models\User;

class AuthHelper
{
    /**
     * বর্তমান ব্যবহারকারী পান
     */
    public static function user(): ?User
    {
        return auth()->user();
    }

    /**
     * ব্যবহারকারী যাচাইকৃত কিনা পরীক্ষা করুন
     */
    public static function isVerified(): bool
    {
        return auth()->check() && auth()->user()->isVerified();
    }

    /**
     * ব্যবহারকারী অ্যাডমিন কিনা পরীক্ষা করুন
     */
    public static function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * ব্যবহারকারী সম্পাদক কিনা পরীক্ষা করুন
     */
    public static function isEditor(): bool
    {
        return auth()->check() && auth()->user()->isEditor();
    }

    /**
     * ব্যবহারকারী লেখক কিনা পরীক্ষা করুন
     */
    public static function isAuthor(): bool
    {
        return auth()->check() && auth()->user()->isAuthor();
    }
}