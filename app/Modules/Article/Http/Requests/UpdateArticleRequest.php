<?php

declare(strict_types=1);

namespace App\Modules\Article\Http\Requests;

use App\Modules\Article\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Article|string|int|null $articleParam */
        $articleParam = $this->route('article');
        $articleId = $articleParam instanceof Article ? $articleParam->id : $articleParam;

        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('articles', 'title')->ignore($articleId),
            ],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'subcategories' => ['sometimes', 'array'],
            'subcategories.*' => ['integer', 'exists:subcategories,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles', 'slug')->ignore($articleId),
            ],
            'excerpt' => ['sometimes', 'nullable', 'string'],
            'content' => ['sometimes', 'nullable', 'string'],
            'featured_image' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'nullable', 'string'],
            'featured' => ['sometimes', 'boolean'],
            'breaking' => ['sometimes', 'boolean'],
            'lead' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer'],
            'meta_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'meta_description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'og_image' => ['sometimes', 'nullable', 'string', 'url'],
            'published_at' => ['sometimes', 'nullable', 'date'],
            'scheduled_publish_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}


