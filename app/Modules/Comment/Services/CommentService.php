<?php

declare(strict_types=1);

namespace App\Modules\Comment\Services;

use App\Modules\Comment\Models\Comment;
use App\Modules\Comment\Models\CommentFlag;
use App\Modules\Comment\Enums\CommentStatus;

class CommentService
{
    public function createComment(array $data, int $userId): Comment
    {
        $data['user_id'] = $userId;
        $data['status'] = CommentStatus::PENDING;

        return Comment::create($data);
    }

    public function updateComment(Comment $comment, array $data): Comment
    {
        $comment->update($data);

        return $comment;
    }

    public function approveComment(Comment $comment): void
    {
        $comment->approve();
    }

    public function rejectComment(Comment $comment): void
    {
        $comment->reject();
    }

    public function flagAsSpam(Comment $comment): void
    {
        $comment->markAsSpam();
    }

    public function flagComment(
        int $commentId,
        int $userId,
        string $reason,
        ?string $description = null
    ): CommentFlag {
        return CommentFlag::create([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'reason' => $reason,
            'description' => $description,
        ]);
    }

    public function getCommentsByArticle(int $articleId, int $perPage = 20)
    {
        return Comment::where(
            'commentable_type',
            'App\Modules\Article\Models\Article'
        )
            ->where('commentable_id', $articleId)
            ->approved()
            ->whereNull('parent_id')
            ->with('replies', 'user')
            ->orderBy('helpful_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getTopComments(int $days = 7, int $limit = 10)
    {
        return Comment::approved()
            ->where('created_at', '>=', now()->subDays($days))
            ->topRated()
            ->limit($limit)
            ->get();
    }

    public function getSuspiciousComments()
    {
        return Comment::where('status', CommentStatus::SPAM)
            ->orWhere(function ($q) {
                $q->where('status', CommentStatus::PENDING)
                    ->where('created_at', '>=', now()->subHours(24))
                    ->where('helpful_count', 0);
            })
            ->get();
    }

    public function cleanupOldComments(int $days = 365): int
    {
        return Comment::where('status', CommentStatus::DELETED)
            ->where('deleted_at', '<=', now()->subDays($days))
            ->forceDelete();
    }
}
