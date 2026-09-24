<?php

declare(strict_types=1);

namespace App\Modules\Audit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user?->id,
                'email' => $this->user?->email,
                'name' => $this->user?->name,
            ],
            'action' => $this->action,
            'model' => [
                'type' => $this->model_type,
                'id' => $this->model_id,
                'name' => $this->model_name,
            ],
            'description' => $this->description,
            'changes' => $this->changes,
            'summary' => $this->getChangeSummary(),
            'request' => [
                'method' => $this->method,
                'url' => $this->url,
                'ip_address' => $this->ip_address,
                'user_agent' => $this->user_agent,
            ],
            'response' => [
                'status_code' => $this->status_code,
                'time_ms' => $this->response_time_ms,
            ],
            'timestamp' => $this->created_at,
        ];
    }
}
