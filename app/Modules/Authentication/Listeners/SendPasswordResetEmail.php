<?php

namespace App\Modules\Authentication\Listeners;

use App\Modules\Authentication\Events\PasswordResetRequested;
use App\Modules\Authentication\Notifications\ResetPassword;

class SendPasswordResetEmail
{
    public function handle(PasswordResetRequested $event): void
    {
        $event->user->notify(new ResetPassword($event->user, $event->token));
    }
}