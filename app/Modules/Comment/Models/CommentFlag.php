<?php

declare(strict_types=1);

namespace App\Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentFlag extends Model
{
    use HasFactory;

    protected $table = 'comment_flags';

    protected $fillable = [
        'comment_id',
        'user_id',
        'reason',
        'description',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            \App\Modules\Authentication\Models\User::class
        );
    }

    public function scopeByReason($query, string $reason)
    {
        return $query->where('reason', $reason);
    }
}
