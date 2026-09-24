<?php

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Events\PasswordResetRequested;
use Illuminate\Support\Facades\DB;

class PasswordResetService
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * পাসওয়ার্ড রিসেট অনুরোধ করুন
     */
    public function requestReset(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            // নিরাপত্তার কারণে বার্তা না দিয়ে নিঃশব্দে ফিরে যান
            return;
        }

        // টোকেন তৈরি করুন
        $token = $this->authService->createPasswordResetToken($user);

        // ইভেন্ট ফায়ার করুন
        event(new PasswordResetRequested($user, $token));
    }

    /**
     * টোকেন যাচাই করুন
     */
    public function verifyToken(string $email, string $token): User
    {
        $user = User::where('email', $email)
            ->where('password_reset_token', hash('sha256', $token))
            ->first();

        if (!$user) {
            throw new \Exception('Invalid reset token');
        }

        if ($user->password_reset_expires_at && $user->password_reset_expires_at->isPast()) {
            throw new \Exception('Reset token has expired');
        }

        return $user;
    }

    /**
     * পাসওয়ার্ড রিসেট সম্পূর্ণ করুন
     */
    public function complete(string $email, string $token, string $password): User
    {
        $user = $this->verifyToken($email, $token);

        DB::beginTransaction();
        try {
            $this->authService->resetPassword($user, $password);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $user;
    }
}