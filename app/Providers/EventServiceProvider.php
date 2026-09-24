<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Modules\Authentication\Events\{
    UserRegistered,
    UserLoggedIn,
    PasswordResetRequested,
    EmailVerified,
};
use App\Modules\Authentication\Listeners\{
    SendVerificationEmail,
    LogUserLogin,
    SendPasswordResetEmail,
};

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendVerificationEmail::class,
        ],
        UserLoggedIn::class => [
            LogUserLogin::class,
        ],
        PasswordResetRequested::class => [
            SendPasswordResetEmail::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}