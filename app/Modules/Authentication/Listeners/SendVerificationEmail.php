<?php

namespace App\Modules\Authentication\Listeners;

use App\Modules\Authentication\Events\UserRegistered;
use App\Modules\Authentication\Notifications\VerifyEmail;

class SendVerificationEmail
{
    public function handle(UserRegistered $event): void
    {
        $event->user->notify(new VerifyEmail($event->user));
    }
}