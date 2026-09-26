<?php

declare(strict_types=1);

namespace App\Modules\Home\Services;

use App\Modules\Article\Models\Article;
use App\Modules\Article\Models\Category;
use App\Modules\Article\Models\District;
use App\Modules\Article\Models\MarketPrice;
use App\Modules\Article\Models\Series;
use App\Modules\Article\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HomePageService
{
    public function getFeaturedArticles(int $limit = 5): Collection
    {
        return Article::query()
            ->featured()
            ->with(['categories'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getLatestArticles(int $limit = 12): Collection
    {
        return Article::query()
            ->published()
            ->with(['categories'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getTrendingArticles(int $days = 7, int $limit = 5): Collection
    {
        return Article::query()
            ->published()
            ->where('published_at', '>=', now()->subDays($days))
            ->orderByDesc('views_count')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getCategories(): Collection
    {
        return Category::query()
            ->withCount([
                'articles as published_articles_count' => function ($query): void {
                    $query->published();
                },
            ])
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }

    public function getBreakingNews(int $limit = 3): Collection
    {
        return Article::query()
            ->breakingNow()
            ->limit($limit)
            ->get();
    }

    public function getLeadStory(): ?Article
    {
        return Article::query()
            ->lead()
            ->with(['categories'])
            ->orderByDesc('published_at')
            ->first();
    }

    public function getRecentUpdates(int $limit = 6): Collection
    {
        return Article::query()
            ->published()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getDistrictsWithNews(int $districtLimit = 4, int $articlesPerDistrict = 3): Collection
    {
        return District::query()
            ->orderBy('order')
            ->limit($districtLimit)
            ->get()
            ->map(function (District $district) use ($articlesPerDistrict) {
                $district->setRelation(
                    'recentArticles',
                    Article::query()
                        ->published()
                        ->inDistrict($district->id)
                        ->orderByDesc('published_at')
                        ->limit($articlesPerDistrict)
                        ->get()
                );

                return $district;
            })
            ->filter(fn (District $district) => $district->recentArticles->isNotEmpty())
            ->values();
    }

    public function getInvestigationArticles(int $limit = 4): Collection
    {
        return Article::query()
            ->investigation()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getOpinionArticles(int $limit = 4): Collection
    {
        return Article::query()
            ->opinion()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getActiveSeries(int $limit = 5): Collection
    {
        return Series::query()
            ->active()
            ->withCount('articles')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getLatestMarketPrices(int $limit = 6): Collection
    {
        $latestDate = MarketPrice::query()->max('price_date');

        if ($latestDate === null) {
            return new Collection();
        }

        return MarketPrice::query()
            ->where('price_date', $latestDate)
            ->orderBy('product_name')
            ->limit($limit)
            ->get();
    }

    public function getLatestVideos(int $limit = 6): Collection
    {
        return Video::query()
            ->published()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function getMostReadArticles(int $limit = 5): Collection
    {
        return Article::query()
            ->published()
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }
}