<?php

declare(strict_types=1);

use App\Modules\Home\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])
        ->name('home');
});