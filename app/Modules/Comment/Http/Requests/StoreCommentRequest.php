<?php

declare(strict_types=1);

namespace App\Modules\Comment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:3', 'max:5000'],
            'parent_id' => ['sometimes', 'nullable', 'exists:comments,id'],
            'commentable_type' => [
                'required',
                'string',
                'in:App\Modules\Article\Models\Article',
            ],
            'commentable_id' => [
                'required',
                'integer',
                'exists:articles,id',
            ],
        ];
    }
}
