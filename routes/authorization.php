<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Authorization\Http\Controllers\RoleController;
use App\Modules\Authorization\Http\Controllers\PermissionController;

Route::prefix('v1')->middleware(['auth:sanctum', 'api'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions']);

    Route::apiResource('permissions', PermissionController::class, [
        'only' => ['index', 'show'],
    ]);

    Route::get('permissions/by-module/{module}', [PermissionController::class, 'byModule']);
    Route::get('permissions/grouped/by-module', [PermissionController::class, 'groupedByModule']);
});