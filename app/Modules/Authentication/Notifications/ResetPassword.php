<?php

namespace App\Modules\Authentication\Notifications;

use App\Modules\Authentication\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected User $user,
        protected string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(
            '/reset-password?token=' . $this->token . '&email=' . urlencode($this->user->email)
        );

        return (new MailMessage)
            ->subject('Reset your password')
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('You requested to reset your password.')
            ->action('Reset Password', $resetUrl)
            ->line('This link will expire in ' . config('authentication.password_reset_token_lifetime') . ' minutes.')
            ->line('If you did not request a password reset, please ignore this email.');
    }
}