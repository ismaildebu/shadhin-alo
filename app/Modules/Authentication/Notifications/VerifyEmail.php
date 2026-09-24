<?php

namespace App\Modules\Authentication\Notifications;

use App\Modules\Authentication\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected User $user
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verifyUrl = url(
            '/verify-email?token=' . $this->user->email_verification_token
        );

        return (new MailMessage)
            ->subject('Verify your email address')
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('Thank you for registering. Please verify your email address.')
            ->action('Verify Email', $verifyUrl)
            ->line('This link will expire in ' . config('authentication.email_verification_token_lifetime') . ' days.')
            ->line('If you did not create this account, please ignore this email.');
    }
}