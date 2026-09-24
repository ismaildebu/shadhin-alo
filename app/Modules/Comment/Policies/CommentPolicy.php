<?php

declare(strict_types=1);

namespace App\Modules\Comment\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Comment\Models\Comment;

class CommentPolicy
{
    public function view(User $user, Comment $comment): bool
    {
        return $comment->isApproved()
            || $user->isAdmin()
            || $user->id === $comment->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('comment.create') || !$user->isBanned();
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->id === $comment->user_id;
    }

    public function approve(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comment.approve') || $user->isAdmin();
    }

    public function flag(User $user): bool
    {
        return true;
    }
}
