<?php

declare(strict_types=1);

namespace App\Modules\Comment\Http\Controllers;

use App\Modules\Comment\Models\Comment;
use App\Modules\Comment\Http\Requests\StoreCommentRequest;
use App\Modules\Comment\Http\Requests\UpdateCommentRequest;
use App\Modules\Comment\Http\Resources\CommentResource;
use App\Modules\Comment\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CommentController
{
    public function __construct(private CommentService $service) {}

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $comment = $this->service->createComment(
            $request->validated(),
            auth()->id()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Comment posted. Awaiting moderation.',
            'data' => new CommentResource($comment),
        ], Response::HTTP_CREATED);
    }

    public function show(Comment $comment): JsonResponse
    {
        $this->authorize('view', $comment);

        return response()->json([
            'status' => 'success',
            'data' => new CommentResource($comment),
            'replies' => CommentResource::collection($comment->replies),
        ]);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $comment = $this->service->updateComment($comment, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'data' => new CommentResource($comment),
        ]);
    }

    public function helpful(Comment $comment): JsonResponse
    {
        $comment->incrementHelpful();

        return response()->json([
            'status' => 'success',
            'helpful_count' => $comment->helpful_count,
        ]);
    }

    public function unhelpful(Comment $comment): JsonResponse
    {
        $comment->incrementUnhelpful();

        return response()->json([
            'status' => 'success',
            'unhelpful_count' => $comment->unhelpful_count,
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully',
        ]);
    }
}
