<?php

declare(strict_types=1);

namespace App\Modules\Article\Services;

use App\Modules\Article\Models\Article;
use App\Modules\Article\Enums\ArticleStatus;
use Illuminate\Pagination\Paginator;

class ArticleService
{
    public function createArticle(array $data): Article
    {
        return Article::create($data);
    }

    public function updateArticle(Article $article, array $data): Article
    {
        $article->update($data);
        return $article;
    }

    public function publishArticle(Article $article): void
    {
        $article->publish();
    }

    public function archiveArticle(Article $article): void
    {
        $article->archive();
    }

    public function getFeaturedArticles(int $limit = 10)
    {
        return Article::featured()->limit($limit)->get();
    }

    public function getArticlesByCategory(string $categorySlug, int $perPage = 15): Paginator
    {
        return Article::published()
            ->byCategory($categorySlug)
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getArticlesByAuthor(int $authorId, int $perPage = 15): Paginator
    {
        return Article::published()
            ->byAuthor($authorId)
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function searchArticles(string $term, int $perPage = 10): Paginator
    {
        return Article::published()
            ->search($term)
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getRelatedArticles(Article $article, int $limit = 5)
    {
        return Article::published()
            ->whereHas('categories', function ($q) use ($article) {
                $q->whereIn('id', $article->categories->pluck('id'));
            })
            ->where('id', '!=', $article->id)
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function incrementViews(Article $article): void
    {
        $article->incrementViews();
    }

    public function getTrendingArticles(int $days = 7, int $limit = 10)
    {
        return Article::published()
            ->where('published_at', '>=', now()->subDays($days))
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
