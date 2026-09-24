<?php

declare(strict_types=1);

namespace App\Modules\Article\Http\Controllers;

use App\Modules\Article\Models\Article;
use App\Modules\Article\Http\Requests\StoreArticleRequest;
use App\Modules\Article\Http\Requests\UpdateArticleRequest;
use App\Modules\Article\Http\Resources\ArticleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArticleController
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        $articles = Article::published()
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => ArticleResource::collection($articles),
            'pagination' => [
                'total' => $articles->total(),
                'per_page' => $articles->perPage(),
                'current_page' => $articles->currentPage(),
            ],
        ]);
    }

    public function featured(): JsonResponse
    {
        $articles = Article::featured()
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ArticleResource::collection($articles),
        ]);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $this->authorize('create', Article::class);

        $data = $request->validated();
        $categories = $data['categories'] ?? null;
        unset($data['categories']);
        $subcategories = $data['subcategories'] ?? null;
        unset($data['subcategories']);

        $article = Article::create(array_merge(
            $data,
            ['author_id' => auth()->id()]
        ));

        if ($categories !== null) {
            $article->categories()->sync($categories);
        }
        if ($subcategories !== null) {
        $article->subcategories()->sync($subcategories);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Article created successfully',
            'data' => new ArticleResource($article),
        ], Response::HTTP_CREATED);
    }

    public function show(Article $article): JsonResponse
    {
        $article->incrementViews();

        return response()->json([
            'status' => 'success',
            'data' => new ArticleResource($article),
        ]);
    }

   public function update(UpdateArticleRequest $request, mixed $article): JsonResponse
{
    // Route Model Binding ফেইল করলে ID দিয়ে নিশ্চিতভাবে বিদ্যমান ইনস্ট্যান্স ফেচ করা
    if (! $article instanceof Article) {
        $article = Article::findOrFail($article);
    }

    $this->authorize('update', $article);

    $data = $request->validated();
    $categories = $data['categories'] ?? null;
    unset($data['categories']);
    $subcategories = $data['subcategories'] ?? null;
    unset($data['subcategories']);

    if (isset($data['title']) && empty($data['slug'])) {
        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
    }

    $article->update($data);

    if ($categories !== null) {
        $article->categories()->sync($categories);
    }
    if ($subcategories !== null) {
        $article->subcategories()->sync($subcategories);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Article updated successfully',
        'data' => new ArticleResource($article->fresh()),
    ]);
}

    public function publish(Article $article): JsonResponse
    {
        $this->authorize('publish', $article);

        $article->publish();

        return response()->json([
            'status' => 'success',
            'message' => 'Article published successfully',
            'data' => new ArticleResource($article->fresh()),
        ]);
    }

    public function archive(Article $article): JsonResponse
    {
        $this->authorize('update', $article);

        $article->archive();

        return response()->json([
            'status' => 'success',
            'message' => 'Article archived successfully',
            'data' => new ArticleResource($article->fresh()),
        ]);
    }

    public function destroy(Article $article): JsonResponse
    {
        $this->authorize('delete', $article);

        $article->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Article deleted successfully',
        ]);
    }

    public function search(): JsonResponse
    {
        $query = request()->input('q');

        if (strlen((string) $query) < 3) {
            return response()->json([
                'status' => 'error',
                'message' => 'Query must be at least 3 characters',
            ], 400);
        }

        $articles = Article::published()
            ->search($query)
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => ArticleResource::collection($articles),
        ]);
    }
}