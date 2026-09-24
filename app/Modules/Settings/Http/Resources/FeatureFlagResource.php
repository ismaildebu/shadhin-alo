<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureFlagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'rollout_percentage' => $this->rollout_percentage,
            'target_users' => $this->target_users,
            'target_roles' => $this->target_roles,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
