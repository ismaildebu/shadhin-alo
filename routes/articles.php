<?php

use App\Modules\Article\Http\Controllers\{ArticleController, CategoryController, TagController};
use Illuminate\Support\Facades\Route;

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/featured', [ArticleController::class, 'featured']);
    Route::get('/search', [ArticleController::class, 'search']);
    Route::get('/trending', [ArticleController::class, 'trending']);
    Route::get('/{article}', [ArticleController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [ArticleController::class, 'store']);
        Route::put('/{article}', [ArticleController::class, 'update']);
        Route::post('/{article}/publish', [ArticleController::class, 'publish']);
        Route::post('/{article}/archive', [ArticleController::class, 'archive']);
        Route::delete('/{article}', [ArticleController::class, 'destroy']);
    });
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);
});

Route::prefix('tags')->group(function () {
    Route::get('/', [TagController::class, 'index']);
    Route::get('/{tag}', [TagController::class, 'show']);
});
