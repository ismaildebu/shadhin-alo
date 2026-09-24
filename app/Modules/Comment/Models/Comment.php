<?php

declare(strict_types=1);

namespace App\Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Comment\Enums\CommentStatus;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'content', 'status', 'user_id', 'parent_id',
        'commentable_type', 'commentable_id',
        'helpful_count', 'unhelpful_count',
    ];

    protected $casts = [
        'status' => CommentStatus::class,
        'helpful_count' => 'int',
        'unhelpful_count' => 'int',
    ];

    protected $with = ['user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Authentication\Models\User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->approved();
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function flags(): HasMany
    {
        return $this->hasMany(CommentFlag::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', CommentStatus::APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', CommentStatus::PENDING);
    }

    public function scopeTopRated($query)
    {
        return $query->orderBy('helpful_count', 'desc')->orderBy('created_at', 'desc');
    }

    public function approve(): void
    {
        $this->update(['status' => CommentStatus::APPROVED]);
    }

    public function reject(): void
    {
        $this->update(['status' => CommentStatus::REJECTED]);
    }

    public function markAsSpam(): void
    {
        $this->update(['status' => CommentStatus::SPAM]);
    }

    public function isApproved(): bool { return $this->status->isApproved(); }
    public function isPending(): bool { return $this->status->isPending(); }
    public function isReply(): bool { return $this->parent_id !== null; }

    public function incrementHelpful(): void { $this->increment('helpful_count'); }
    public function incrementUnhelpful(): void { $this->increment('unhelpful_count'); }

    public function getHelpfulPercentage(): float
    {
        $total = $this->helpful_count + $this->unhelpful_count;
        return $total > 0 ? ($this->helpful_count / $total) * 100 : 0;
    }

    public function getRepliesCount(): int { return $this->replies()->count(); }
}
