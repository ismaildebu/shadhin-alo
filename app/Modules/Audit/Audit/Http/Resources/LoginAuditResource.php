<?php

declare(strict_types=1);

namespace App\Modules\Audit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginAuditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user?->id,
                'email' => $this->email,
                'name' => $this->user?->name,
            ],
            'action' => $this->action,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'status' => $this->getStatusBadge(),
            'is_successful' => $this->is_successful,
            'failure_reason' => $this->failure_reason,
            'location' => $this->location,
            'device_info' => $this->device_info,
            'timestamp' => $this->created_at,
        ];
    }
}
