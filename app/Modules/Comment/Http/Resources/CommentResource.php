<?php

declare(strict_types=1);

namespace App\Modules\Comment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'avatar' => $this->user?->profile?->avatar,
            ],
            'is_reply' => $this->isReply(),
            'helpful_count' => $this->helpful_count,
            'unhelpful_count' => $this->unhelpful_count,
            'helpful_percentage' => round($this->getHelpfulPercentage(), 1),
            'replies_count' => $this->getRepliesCount(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
