<?php

declare(strict_types=1);

namespace App\Modules\Article\Http\Controllers;

use App\Modules\Article\Models\Article;
use App\Modules\Article\Models\Category;
use App\Modules\Article\Models\Subcategory;
use App\Modules\Article\Models\Tag;
use App\Modules\Article\Http\Requests\StoreArticleRequest;
use App\Modules\Article\Http\Requests\UpdateArticleRequest;
use App\Modules\Article\Http\Resources\ArticleResource;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class ArticleController
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        $articles = Article::published()
            ->orderBy('sort_order', 'asc')
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

    public function create(): View
    {
        $this->authorize('create', Article::class);

        return view('articles.create', [
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function storeWeb(Request $request): RedirectResponse
    {
        $this->authorize('create', Article::class);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:100'],
            'featured_image' => ['nullable', 'string', 'url'],
            'status' => ['required', 'in:draft,pending_review,published,archived'],
            'featured' => ['sometimes', 'boolean'],
            'breaking' => ['sometimes', 'boolean'],
            'lead' => ['sometimes', 'boolean'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'subcategories' => ['sometimes', 'array'],
            'subcategories.*' => ['integer', 'exists:subcategories,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $categories = $data['categories'] ?? null;
        unset($data['categories']);
        $subcategories = $data['subcategories'] ?? null;
        unset($data['subcategories']);
        $tags = $data['tags'] ?? null;
        unset($data['tags']);

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
        if ($tags !== null) {
            $article->tags()->sync($tags);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'আর্টিকেল সফলভাবে তৈরি হয়েছে।');
    }

    public function manage(Request $request): View
    {
        $this->authorize('viewAny', Article::class);

        $status = $request->query('status');
        $allowedStatuses = ['draft', 'pending_review', 'published', 'archived'];

        $query = Article::query()->orderBy('created_at', 'desc');

        if ($status !== null && in_array($status, $allowedStatuses, true)) {
            $query->where('status', $status);
        }

        $articles = $query->paginate(20)->withQueryString();

        return view('articles.manage', [
            'articles' => $articles,
            'currentStatus' => $status,
        ]);
    }

    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        $article->load(['categories', 'subcategories', 'tags']);

        return view('articles.edit', [
            'article' => $article,
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'selectedCategoryIds' => $article->categories->pluck('id')->all(),
            'selectedSubcategoryIds' => $article->subcategories->pluck('id')->all(),
            'selectedTagIds' => $article->tags->pluck('id')->all(),
        ]);
    }

    public function updateWeb(Request $request, Article $article): RedirectResponse
    {
        $this->authorize('update', $article);

        $data = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('articles', 'title')->ignore($article->id),
            ],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:100'],
            'featured_image' => ['nullable', 'string', 'url'],
            'status' => ['required', 'in:draft,pending_review,published,archived'],
            'featured' => ['sometimes', 'boolean'],
            'breaking' => ['sometimes', 'boolean'],
            'lead' => ['sometimes', 'boolean'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'subcategories' => ['sometimes', 'array'],
            'subcategories.*' => ['integer', 'exists:subcategories,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $categories = $data['categories'] ?? [];
        unset($data['categories']);
        $subcategories = $data['subcategories'] ?? [];
        unset($data['subcategories']);
        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $data['featured'] = $request->boolean('featured');
        $data['breaking'] = $request->boolean('breaking');
        $data['lead'] = $request->boolean('lead');

        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);

        if ($data['status'] === 'published' && empty($article->published_at)) {
                $data['published_at'] = now();
            }

            if ($data['status'] !== 'published') {
                $data['published_at'] = null;
            }


        $article->update($data);

        $article->categories()->sync($categories);
        $article->subcategories()->sync($subcategories);
        $article->tags()->sync($tags);

        return redirect()
            ->route('articles.manage')
            ->with('success', 'আর্টিকেল সফলভাবে আপডেট হয়েছে।');
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $this->authorize('create', Article::class);

        $data = $request->validated();
        $categories = $data['categories'] ?? null;
        unset($data['categories']);
        $subcategories = $data['subcategories'] ?? null;
        unset($data['subcategories']);
        $tags = $data['tags'] ?? null;
        unset($data['tags']);

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
        if ($tags !== null) {
            $article->tags()->sync($tags);
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
        if (! $article instanceof Article) {
            $article = Article::findOrFail($article);
        }

        $this->authorize('update', $article);

        $data = $request->validated();
        $categories = $data['categories'] ?? null;
        unset($data['categories']);
        $subcategories = $data['subcategories'] ?? null;
        unset($data['subcategories']);
        $tags = $data['tags'] ?? null;
        unset($data['tags']);

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
        if ($tags !== null) {
            $article->tags()->sync($tags);
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

    public function breaking(): JsonResponse
    {
        $articles = Article::breaking()
            ->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ArticleResource::collection($articles),
        ]);
    }

    public function lead(): JsonResponse
    {
        $articles = Article::lead()
            ->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ArticleResource::collection($articles),
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