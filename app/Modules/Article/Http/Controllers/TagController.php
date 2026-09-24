<?php

declare(strict_types=1);

namespace App\Modules\Article\Http\Controllers;

use App\Modules\Article\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController
{
    public function index(): JsonResponse
    {
        $tags = Tag::with('articles')->get();

        return response()->json([
            'status' => 'success',
            'data' => $tags,
        ]);
    }

    public function show(Tag $tag): JsonResponse
    {
        $articles = $tag->articles()->published()->orderBy('published_at', 'desc')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'tag' => $tag,
                'articles' => $articles,
            ],
        ]);
    }
}
