<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Services;

use App\Modules\Article\Enums\ArticleStatus;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Models\Category;
use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function getStatistics(): array
    {
        return [
            'total_articles' => Article::query()->count(),
            'published_articles' => Article::query()
                ->where('status', ArticleStatus::PUBLISHED)
                ->count(),
            'draft_articles' => Article::query()
                ->where('status', ArticleStatus::DRAFT)
                ->count(),
            'pending_articles' => Article::query()
                ->where('status', ArticleStatus::PENDING_REVIEW)
                ->count(),
            'total_categories' => Category::query()->count(),
            'total_users' => User::query()->count(),
            'total_views' => (int) Article::query()->sum('views_count'),
        ];
    }

    public function getRecentArticles(int $limit = 8): Collection
    {
        return Article::query()
            ->with(['author'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getPopularArticles(int $limit = 5): Collection
    {
        return Article::query()
            ->with(['author'])
            ->published()
            ->orderByDesc('views_count')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}