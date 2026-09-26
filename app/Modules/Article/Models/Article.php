<?php

declare(strict_types=1);

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Article\Enums\ArticleStatus;
use App\Modules\Audit\Traits\Auditable;
use App\Modules\Comment\Traits\HasComments;

class Article extends Model
{
    use HasFactory, SoftDeletes, Auditable, HasComments;

    protected static function booted(): void
    {
        static::creating(function (Article $article): void {
            $article->status ??= ArticleStatus::DRAFT;

            if (empty($article->slug) && ! empty($article->title)) {
                $article->slug = \Illuminate\Support\Str::slug($article->title);
            }
        });

        static::updating(function (Article $article): void {
            if ($article->isDirty('title') && ! $article->isDirty('slug')) {
                $article->slug = \Illuminate\Support\Str::slug($article->title);
            }
        });
    }

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Database\Factories\ArticleFactory::new();
    }

    protected $table = 'articles';

    protected $fillable = [
        'author_id',
        'series_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'content_type',
        'featured',
        'breaking',
        'lead',
        'published_at',
        'scheduled_publish_at',
        'sort_order',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'status' => ArticleStatus::class,
        'featured' => 'bool',
        'breaking' => 'bool',
        'lead' => 'bool',
        'sort_order' => 'int',
        'views_count' => 'int',
        'published_at' => 'datetime',
        'scheduled_publish_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $with = ['author'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Authentication\Models\User::class, 'author_id');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class, 'series_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_categories');
    }

    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class, 'article_subcategories');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }

    public function districts(): BelongsToMany
    {
        return $this->belongsToMany(District::class, 'article_district');
    }

    public function scopePublished($query)
    {
        return $query->where('status', ArticleStatus::PUBLISHED)->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true)->published();
    }

    public function scopeBreaking($query)
    {
        return $query->where('breaking', true)->published();
    }

    public function scopeLead($query)
    {
        return $query->where('lead', true)->published();
    }

    public function scopeByAuthor($query, int $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeBreakingNow($query)
    {
        return $query->where('breaking', true)
            ->published()
            ->orderByDesc('published_at')
            ->limit(3);
    }

    public function scopeOpinion($query)
    {
        return $query->where('content_type', 'opinion')->published();
    }

    public function scopeInvestigation($query)
    {
        return $query->where('content_type', 'investigation')->published();
    }

    public function scopeInDistrict($query, int $districtId)
    {
        return $query->whereHas('districts', function ($q) use ($districtId) {
            $q->where('districts.id', $districtId);
        });
    }

    public function publish(): void
    {
        $this->update([
            'status' => ArticleStatus::PUBLISHED,
            'published_at' => now(),
            'scheduled_publish_at' => null,
        ]);
    }

    public function unpublish(): void
    {
        $this->update([
            'status' => ArticleStatus::DRAFT,
            'published_at' => null,
        ]);
    }

    public function archive(): void
    {
        $this->update(['status' => ArticleStatus::ARCHIVED]);
    }

    public function isPublished(): bool { return $this->status?->isPublished() ?? false; }
    public function isDraft(): bool { return $this->status?->isDraft() ?? false; }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getReadingTime(): int
    {
        $cleanContent = strip_tags($this->content ?? '');
        $wordCount = str_word_count($cleanContent);

        return max(1, (int) ceil($wordCount / 200));
    }

    public function getSeoTitle(): string
    {
        return $this->meta_title ?: ($this->title ?? '');
    }

    public function getSeoDescription(): string
    {
        return $this->meta_description ?: ($this->excerpt ?? '');
    }

    public function getOgImage(): ?string
    {
        return $this->og_image ?: $this->featured_image;
    }
}