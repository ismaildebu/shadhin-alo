<?php

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\User;

class TokenService
{
    /**
     * ব্যবহারকারীর জন্য API টোকেন তৈরি করুন
     */
    public function createToken(User $user, string $name = 'api-token'): string
    {
        $expiresAt = now()->addDays(config('authentication.api_token_lifetime'));

        return $user->createToken($name, ['*'], $expiresAt)->plainTextToken;
    }

    /**
     * নামযুক্ত টোকেন সাফ করুন
     */
    public function deleteToken(User $user, string $name): void
    {
        $user->tokens()->where('name', $name)->delete();
    }

    /**
     * সব টোকেন সাফ করুন
     */
    public function deleteAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * টোকেন সতেজ করুন
     */
    public function refreshToken(User $user, string $tokenId): string
    {
        $user->tokens()->where('id', $tokenId)->delete();
        return $this->createToken($user);
    }
}