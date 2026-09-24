<?php

use App\Modules\Comment\Http\Controllers\{CommentController, CommentRatingController};
use Illuminate\Support\Facades\Route;

Route::prefix('comments')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        // Create comment
        Route::post('/', [CommentController::class, 'store']);
        
        // Update own comment
        Route::put('/{comment}', [CommentController::class, 'update']);
        
        // Mark helpful/unhelpful
        Route::post('/{comment}/helpful', [CommentController::class, 'helpful']);
        Route::post('/{comment}/unhelpful', [CommentController::class, 'unhelpful']);
        
        // Delete own comment
        Route::delete('/{comment}', [CommentController::class, 'destroy']);
        
        // Rate comment (article rating)
        Route::post('/ratings', [CommentRatingController::class, 'store']);
    });
    
    // View single comment
    Route::get('/{comment}', [CommentController::class, 'show']);
});

// Article comments endpoint
Route::prefix('articles/{article}/comments')->group(function () {
    Route::get('/', function (\App\Modules\Article\Models\Article $article) {
        return response()->json([
            'status' => 'success',
            'data' => \App\Modules\Comment\Http\Resources\CommentResource::collection(
                $article->comments()->approved()->whereNull('parent_id')->paginate(20)
            ),
        ]);
    });
    
    Route::middleware('auth:sanctum')->post('/', [CommentController::class, 'store']);
});
