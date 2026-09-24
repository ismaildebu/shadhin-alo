<?php

declare(strict_types=1);

namespace App\Modules\Comment\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Modules\Comment\Models\Comment;

trait HasComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function approvedComments(): MorphMany
    {
        return $this->comments()->approved();
    }

    public function pendingComments(): MorphMany
    {
        return $this->comments()->pending();
    }

    public function getCommentsCount(): int
    {
        return $this->approvedComments()->count();
    }

    public function getAverageRating(): float
    {
        return $this->approvedComments()->avg('helpful_count') ?? 0;
    }
}
