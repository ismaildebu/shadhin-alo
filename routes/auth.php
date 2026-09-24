<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Authentication\Http\Controllers\{
    RegisterController,
    LoginController,
    PasswordResetController,
    VerificationController,
};

Route::prefix('api/v1/auth')->group(function () {
    // পাবলিক রুট
    Route::post('/register', [RegisterController::class, 'store'])->name('auth.register');
    Route::post('/login', [LoginController::class, 'store'])->name('auth.login');

    // পাসওয়ার্ড রিসেট
    Route::post('/forgot-password', [PasswordResetController::class, 'requestReset'])->name('auth.forgot-password');
    Route::post('/verify-reset-token', [PasswordResetController::class, 'verifyToken'])->name('auth.verify-reset-token');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('auth.reset-password');

    // ইমেইল যাচাইকরণ
    Route::post('/email/verify', [VerificationController::class, 'verify'])->name('auth.email.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('auth.email.resend');

    // সুরক্ষিত রুট
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');
    });
});