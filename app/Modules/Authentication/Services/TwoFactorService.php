<?php

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\User;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * ২FA সক্ষম করার জন্য সিক্রেট তৈরি করুন
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * ২FA QR কোড তৈরি করুন
     */
    public function generateQRCode(User $user, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
    }

    /**
     * OTP যাচাই করুন
     */
    public function verifyOTP(User $user, string $otp): bool
    {
        if (!$user->two_factor_enabled || !$user->two_factor_secret) {
            return false;
        }

        return $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $otp,
            2 // ২ মিনিট সহনশীলতা
        );
    }

    /**
     * ব্যাকআপ কোড যাচাই করুন
     */
    public function verifyBackupCode(User $user, string $code): bool
    {
        if (!$user->two_factor_backup_codes) {
            return false;
        }

        $codes = $user->two_factor_backup_codes;

        if (in_array($code, $codes)) {
            // কোড ব্যবহার করা হয়েছে, সরান
            $codes = array_filter($codes, fn ($c) => $c !== $code);
            $user->update(['two_factor_backup_codes' => array_values($codes)]);

            return true;
        }

        return false;
    }
}