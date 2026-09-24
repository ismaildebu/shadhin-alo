<?php

declare(strict_types=1);

namespace App\Modules\Article\Http\Controllers;

use App\Modules\Article\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController
{
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('order')->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    public function show(Category $category): JsonResponse
    {
        $articles = $category->articles()->published()->orderBy('published_at', 'desc')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category,
                'articles' => $articles,
            ],
        ]);
    }
}
