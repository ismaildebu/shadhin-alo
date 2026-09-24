<?php

use App\Modules\Audit\Http\Controllers\ActivityController;
use App\Modules\Audit\Http\Controllers\LoginAuditController;
use App\Modules\Audit\Http\Controllers\PermissionAuditController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('audit')->group(function () {
        // Activities
        Route::get('/activities', [ActivityController::class, 'index']);
        Route::get('/activities/summary', [ActivityController::class, 'summary']);
        Route::get('/activities/export', [ActivityController::class, 'export']);
        Route::get('/activities/user/{userId}', [ActivityController::class, 'byUser']);
        Route::get('/activities/model/{modelType}/{modelId}', [ActivityController::class, 'byModel']);
        Route::get('/activities/{activity}', [ActivityController::class, 'show']);

        // Login Audits
        Route::get('/login-audits', [LoginAuditController::class, 'index']);
        Route::get('/login-audits/summary', [LoginAuditController::class, 'summary']);
        Route::get('/login-audits/failed', [LoginAuditController::class, 'failed']);
        Route::get('/login-audits/suspicious', [LoginAuditController::class, 'suspiciousActivity']);
        Route::get('/login-audits/user/{userId}', [LoginAuditController::class, 'byUser']);

        // Permission Audits
        Route::get('/permission-audits', [PermissionAuditController::class, 'index']);
        Route::get('/permission-audits/summary', [PermissionAuditController::class, 'summary']);
        Route::get('/permission-audits/denied', [PermissionAuditController::class, 'denied']);
        Route::get('/permission-audits/user/{userId}', [PermissionAuditController::class, 'byUser']);
    });
});
