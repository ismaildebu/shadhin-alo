<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Authentication\Http\Controllers\WebLoginController;
use App\Modules\Authentication\Http\Controllers\{
    RegisterController,
    LoginController,
    PasswordResetController,
    VerificationController,
};

/*
|--------------------------------------------------------------------------
| Web Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('web')->group(function () {
    Route::get('/login', [WebLoginController::class, 'create'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [WebLoginController::class, 'store'])
        ->middleware('guest')
        ->name('login.store');

    Route::post('/logout', [WebLoginController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| API Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api/v1/auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])
        ->name('auth.register');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('auth.login');

    Route::post('/forgot-password', [PasswordResetController::class, 'requestReset'])
        ->name('auth.forgot-password');

    Route::post('/verify-reset-token', [PasswordResetController::class, 'verifyToken'])
        ->name('auth.verify-reset-token');

    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
        ->name('auth.reset-password');

    Route::post('/email/verify', [VerificationController::class, 'verify'])
        ->name('auth.email.verify');

    Route::post('/email/resend', [VerificationController::class, 'resend'])
        ->name('auth.email.resend');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('auth.logout');
    });
});