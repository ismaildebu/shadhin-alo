<?php

namespace App\Modules\Authentication\Listeners;

use App\Modules\Authentication\Events\UserLoggedIn;
use App\Modules\Authentication\Models\LoginAttempt;

class LogUserLogin
{
    public function handle(UserLoggedIn $event): void
    {
        LoginAttempt::create([
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'successful' => true,
        ]);
    }
}