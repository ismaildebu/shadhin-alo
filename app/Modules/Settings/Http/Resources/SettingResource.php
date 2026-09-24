<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->value,
            'type' => $this->type,
            'description' => $this->description,
            'module' => $this->module,
            'is_public' => $this->is_public,
            'is_encrypted' => $this->is_encrypted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
