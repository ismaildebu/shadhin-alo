<?php

declare(strict_types=1);

namespace App\Modules\Audit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionAuditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user?->id,
                'email' => $this->user?->email,
            ],
            'permission' => $this->permission,
            'model' => [
                'type' => $this->model_type,
                'id' => $this->model_id,
            ],
            'is_allowed' => $this->is_allowed,
            'status' => $this->is_allowed ? '✅ Allowed' : '❌ Denied',
            'ip_address' => $this->ip_address,
            'url' => $this->url,
            'timestamp' => $this->created_at,
        ];
    }
}
