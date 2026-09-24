<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Comment\Policies\CommentPolicy;
use App\Modules\Comment\Models\Comment;

class CommentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Gate::policy(Comment::class, CommentPolicy::class);
    }
}
