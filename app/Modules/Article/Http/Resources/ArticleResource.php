<?php

declare(strict_types=1);

namespace App\Modules\Article\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource === null) {
            return [];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $this->featured_image,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'featured' => $this->featured,
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
                'email' => $this->author?->email,
            ],
            'published_at' => $this->published_at,
            'scheduled_publish_at' => $this->scheduled_publish_at,
            'views_count' => $this->views_count,
            'reading_time' => $this->getReadingTime() . ' min',
            'seo' => [
                'title' => $this->getSeoTitle(),
                'description' => $this->getSeoDescription(),
                'og_image' => $this->getOgImage(),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}