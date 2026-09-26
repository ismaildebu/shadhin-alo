<?php

declare(strict_types=1);

namespace App\Modules\Article\Models;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = [
        'title',
        'slug',
        'video_url',
        'thumbnail',
        'description',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Video $video): void {
            if (empty($video->slug) && ! empty($video->title)) {
                $video->slug = \Illuminate\Support\Str::slug($video->title);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}