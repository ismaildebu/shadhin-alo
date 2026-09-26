<?php

use App\Modules\Article\Http\Controllers\{ArticleController, CategoryController, TagController};
use Illuminate\Support\Facades\Route;

// Web form/list routes — MUST be defined before 'articles/{article}' API route,
// otherwise 'articles/create', 'articles/manage', 'articles/{article}/edit'
// would be matched incorrectly by the generic {article} route below.
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])
        ->name('articles.create');

    Route::post('/articles/create', [ArticleController::class, 'storeWeb'])
        ->name('articles.store.web');

    Route::get('/articles/manage', [ArticleController::class, 'manage'])
        ->name('articles.manage');

    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])
        ->name('articles.edit');

    Route::put('/articles/{article}/edit', [ArticleController::class, 'updateWeb'])
        ->name('articles.update.web');
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/featured', [ArticleController::class, 'featured']);
    Route::get('/breaking', [ArticleController::class, 'breaking']);
    Route::get('/lead', [ArticleController::class, 'lead']);
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