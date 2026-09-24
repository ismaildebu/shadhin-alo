<?php

declare(strict_types=1);

namespace App\Modules\Article\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:articles,title'],
            'slug' => ['required', 'string', 'max:255', 'unique:articles,slug'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:100'],
            'featured_image' => ['sometimes', 'string', 'url'],
            'status' => ['sometimes', 'in:draft,pending_review,published,archived'],
            'featured' => ['sometimes', 'boolean'],
            'meta_title' => ['sometimes', 'string', 'max:255'],
            'meta_description' => ['sometimes', 'string', 'max:500'],
            'og_image' => ['sometimes', 'string', 'url'],
            'scheduled_publish_at' => ['sometimes', 'date', 'after:now'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'subcategories' => ['sometimes', 'array'],
            'subcategories.*' => ['integer', 'exists:subcategories,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }
}
