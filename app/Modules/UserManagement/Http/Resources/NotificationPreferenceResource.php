<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'notification_type' => $this->notification_type,
            'enabled' => $this->isEnabled(),
            'channels' => [
                'email' => $this->email_enabled,
                'push' => $this->push_enabled,
                'in_app' => $this->in_app_enabled,
                'sms' => $this->sms_enabled,
            ],
            'frequency' => $this->frequency,
            'quiet_hours' => [
                'enabled' => $this->quiet_hours_enabled,
                'start' => $this->quiet_hours_start,
                'end' => $this->quiet_hours_end,
                'in_quiet_hours' => $this->isInQuietHours(),
            ],
            'timezone' => $this->timezone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
