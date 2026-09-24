<?php

declare(strict_types=1);

namespace App\Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Comment\Enums\RatingValue;

class CommentRating extends Model
{
    use HasFactory;

    protected $table = 'comment_ratings';

    protected $fillable = [
        'comment_id',
        'article_id',
        'user_id',
        'rating',
    ];

    protected $casts = [
        'rating' => RatingValue::class,
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(
            \App\Modules\Article\Models\Article::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            \App\Modules\Authentication\Models\User::class
        );
    }
}
