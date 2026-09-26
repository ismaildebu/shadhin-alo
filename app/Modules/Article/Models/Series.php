<?php

declare(strict_types=1);

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    protected $table = 'series';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Series $series): void {
            if (empty($series->slug) && ! empty($series->title)) {
                $series->slug = \Illuminate\Support\Str::slug($series->title);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'series_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}