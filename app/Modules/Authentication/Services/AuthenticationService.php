<?php

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Events\UserRegistered;
use App\Modules\Authentication\Events\UserLoggedIn;
use App\Modules\Authentication\Exceptions\InvalidCredentialsException;
use App\Modules\Authentication\Exceptions\EmailNotVerifiedException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationService
{
    /**
     * ব্যবহারকারী নিবন্ধন করুন
     */
    public function register(array $data): User
{
    $user = new User([
        'uuid' => Str::uuid(),
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'phone' => $data['phone'] ?? null,
        'status' => 'active',
        'activated_at' => now(),
        'email_verification_token' => Str::random(60),
        'email_verification_expires_at' => now()->addDays(
            config('authentication.email_verification_token_lifetime')
        ),
        'gdpr_accepted_at' => now(),
        'marketing_consent' => $data['marketing_consent'] ?? false,
        'marketing_consent_at' => isset($data['marketing_consent']) && $data['marketing_consent']
            ? now()
            : null,
    ]);

    $user->password = $data['password'];
    $user->save();

    event(new UserRegistered($user));

    return $user;
}
    

    /**
     * ইমেইল যাচাই করুন
     */
    public function verifyEmail(User $user): void
    {
        // টোকেন যাচাই করুন
        if (!$user->email_verification_token) {
            throw new \Exception('Email already verified');
        }

        if ($user->email_verification_expires_at && $user->email_verification_expires_at->isPast()) {
            throw new \Exception('Verification token has expired');
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ]);
    }

    /**
     * ক্রেডেনশিয়াল দিয়ে লগইন করুন
     */
    public function login(string $email, string $password, bool $rememberMe = false): User
    {
        // ব্যবহারকারী খুঁজুন
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new InvalidCredentialsException('User not found');
        }

        // পাসওয়ার্ড যাচাই করুন
        if (!Hash::check($password, $user->password)) {
            $user->incrementLoginAttempts();
            throw new InvalidCredentialsException('Invalid password');
        }

        // ব্যবহারকারী লক করা আছে কিনা পরীক্ষা করুন
        if ($user->isLocked()) {
            throw new InvalidCredentialsException('Account is locked. Please try again later');
        }

        // ব্যবহারকারী যাচাইকৃত কিনা পরীক্ষা করুন
        if (config('authentication.email_verification_required') && !$user->isVerified()) {
            throw new EmailNotVerifiedException('Please verify your email before logging in');
        }

        // ব্যবহারকারী সক্রিয় কিনা পরীক্ষা করুন
        if ($user->status !== 'active') {
            throw new InvalidCredentialsException('Your account is ' . $user->status);
        }

        // লগইন প্রচেষ্টা রিসেট করুন
        $user->resetLoginAttempts();

        // লগইন তথ্য আপডেট করুন
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'last_login_user_agent' => request()->userAgent(),
        ]);

        // ইভেন্ট ফায়ার করুন
        event(new UserLoggedIn($user));

        return $user;
    }

    /**
     * পাসওয়ার্ড রিসেট টোকেন তৈরি করুন
     */
    public function createPasswordResetToken(User $user): string
    {
        $token = Str::random(60);

        $user->update([
            'password_reset_token' => hash('sha256', $token),
            'password_reset_expires_at' => now()->addMinutes(
                config('authentication.password_reset_token_lifetime')
            ),
        ]);

        return $token;
    }

    /**
     * পাসওয়ার্ড রিসেট করুন
     */
    public function resetPassword(User $user, string $newPassword): void
    {
        if (!$user->password_reset_expires_at || $user->password_reset_expires_at->isPast()) {
            throw new \Exception('Password reset token has expired');
        }

        $user->update([
            'password' => $newPassword,
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
        ]);

        // সব টোকেন সাফ করুন
        $user->tokens()->delete();
    }

    /**
     * পাসওয়ার্ড পরিবর্তন করুন
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new InvalidCredentialsException('Current password is incorrect');
        }

        if (Hash::check($newPassword, $user->password)) {
            throw new \Exception('New password cannot be same as current password');
        }

        $user->update([
            'password' => $newPassword,
        ]);
    }

    /**
     * ব্যবহারকারীকে সদন্দ করুন (লজআউট)
     */
    public function logout(User $user, ?string $tokenId = null): void
    {
        if ($tokenId) {
            // নির্দিষ্ট টোকেন সাফ করুন
            $user->tokens()->where('id', $tokenId)->delete();
        } else {
            // সব টোকেন সাফ করুন
            $user->tokens()->delete();
        }
    }

    /**
     * ব্যবহারকারীকে সাসপেন্ড করুন
     */
    public function suspendUser(User $user, ?string $reason = null): void
    {
        $user->update([
            'status' => 'suspended',
            'deactivated_at' => now(),
            'deactivation_reason' => $reason,
        ]);

        // সব টোকেন সাফ করুন
        $user->tokens()->delete();
    }

    /**
     * ব্যবহারকারীকে ব্যান করুন
     */
    public function banUser(User $user, ?string $reason = null): void
    {
        $user->update([
            'status' => 'banned',
            'deactivated_at' => now(),
            'deactivation_reason' => $reason,
        ]);

        $user->tokens()->delete();
    }
}