<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'module' => $this->module,
            'is_system' => $this->is_system,
            'full_name' => $this->getFullName(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
