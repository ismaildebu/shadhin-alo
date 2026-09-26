<?php

declare(strict_types=1);

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class District extends Model
{
    protected $table = 'districts';

    protected $fillable = [
        'name',
        'slug',
        'division',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (District $district): void {
            if (empty($district->slug) && ! empty($district->name)) {
                $district->slug = \Illuminate\Support\Str::slug($district->name);
            }
        });
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_district');
    }
}