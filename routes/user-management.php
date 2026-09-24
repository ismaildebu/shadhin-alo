<?php

use Illuminate\Support\Facades\Route;
use App\Modules\UserManagement\Http\Controllers\ProfileController;
use App\Modules\UserManagement\Http\Controllers\PreferenceController;

// Public profile route
Route::middleware(['api'])->group(function () {
    Route::get('profile/public/{userId}', [ProfileController::class, 'getPublicProfile']);
});

Route::middleware(['auth:sanctum', 'api'])->group(function () {
    // Profile routes
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::post('profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::post('profile/cover-image', [ProfileController::class, 'updateCoverImage']);

    // Preference routes
    Route::get('preferences', [PreferenceController::class, 'show']);
    Route::put('preferences', [PreferenceController::class, 'update']);
    Route::put('preferences/theme', [PreferenceController::class, 'updateTheme']);
    Route::put('preferences/language', [PreferenceController::class, 'updateLanguage']);

    // Notification preference routes
    Route::get('preferences/notifications', [PreferenceController::class, 'getNotificationPreferences']);
    Route::put('preferences/notifications/{notificationType}', [PreferenceController::class, 'updateNotificationPreference']);
    Route::post('preferences/notifications/{notificationType}/disable', [PreferenceController::class, 'disableNotifications']);
});