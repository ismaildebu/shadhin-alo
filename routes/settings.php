<?php

use App\Modules\Settings\Http\Controllers\SettingController;
use App\Modules\Settings\Http\Controllers\EmailSettingController;
use App\Modules\Settings\Http\Controllers\FeatureFlagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::get('/system', [SettingController::class, 'getSystemSettings']);
        Route::get('/module/{module}', [SettingController::class, 'byModule']);
        Route::get('/{setting}', [SettingController::class, 'show']);
        Route::put('/{setting}', [SettingController::class, 'update']);
    });

    // Email Settings
    Route::prefix('email-settings')->group(function () {
        Route::get('/', [EmailSettingController::class, 'index']);
        Route::put('/', [EmailSettingController::class, 'update']);
        Route::post('/test', [EmailSettingController::class, 'test']);
    });

    // Feature Flags
    Route::prefix('feature-flags')->group(function () {
        Route::get('/', [FeatureFlagController::class, 'index']);
        Route::post('/', [FeatureFlagController::class, 'store']);
        Route::get('/{flag}', [FeatureFlagController::class, 'show']);
        Route::put('/{flag}', [FeatureFlagController::class, 'update']);
        Route::put('/{flag}/toggle', [FeatureFlagController::class, 'toggle']);
        Route::get('/check/{slug}', [FeatureFlagController::class, 'isEnabled']);
    });
});
