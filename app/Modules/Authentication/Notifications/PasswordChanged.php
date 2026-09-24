<?php

namespace App\Modules\Authentication\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your password has been changed')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your password was successfully changed.')
            ->line('If this was not you, please reset your password immediately.')
            ->action('Visit our website', url('/'))
            ->line('If you have any questions, please contact our support team.');
    }
}