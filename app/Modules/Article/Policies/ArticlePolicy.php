<?php

declare(strict_types=1);

namespace App\Modules\Article\Policies;

use App\Modules\Authentication\Models\User;
use App\Modules\Article\Models\Article;

class ArticlePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Article $article): bool
    {
        return $article->isPublished()
            || ($user && ($user->isAdmin() || $user->id === $article->author_id));
    }

    public function create(User $user): bool
    {
        return $user->hasRole('editor')
            || $user->hasRole('admin')
            || $user->hasPermission('article.create')
            || $user->isAdmin();
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasRole('editor')
            || $user->hasRole('admin')
            || $user->hasPermission('article.edit')
            || $user->id === $article->author_id;
    }

    public function publish(User $user, Article $article): bool
    {
        return $user->hasRole('editor')
            || $user->hasRole('admin')
            || $user->hasPermission('article.publish');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasRole('admin')
            || $user->hasPermission('article.delete');
    }
}