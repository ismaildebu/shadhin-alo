<?php

declare(strict_types=1);

namespace App\Modules\Comment\Http\Requests;

use App\Modules\Comment\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCommentRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'comment_id' => [
                'required',
                'integer',
                'exists:comments,id',
            ],
            'article_id' => [
                'required',
                'integer',
                'exists:articles,id',
            ],
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $comment = Comment::query()->find($this->integer('comment_id'));

                if (
                    $comment === null ||
                    $comment->commentable_type !== \App\Modules\Article\Models\Article::class ||
                    (int) $comment->commentable_id !== $this->integer('article_id')
                ) {
                    $validator->errors()->add(
                        'comment_id',
                        'The comment does not belong to the selected article.'
                    );
                }
            },
        ];
    }
}