<?php

declare(strict_types=1);

namespace App\Modules\Comment\Http\Controllers;

use App\Modules\Comment\Http\Requests\StoreCommentRatingRequest;
use App\Modules\Comment\Models\CommentRating;
use Illuminate\Http\JsonResponse;

class CommentRatingController
{
    public function store(StoreCommentRatingRequest $request): JsonResponse
    {
        $userId = (int) auth()->id();

        $rating = CommentRating::updateOrCreate(
            [
                'comment_id' => $request->integer('comment_id'),
                'article_id' => $request->integer('article_id'),
                'user_id' => $userId,
            ],
            [
                'rating' => $request->integer('rating'),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Rating recorded',
            'data' => $rating,
        ]);
    }
}