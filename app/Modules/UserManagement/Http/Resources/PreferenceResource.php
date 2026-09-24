<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'theme' => $this->theme,
            'language' => $this->language,
            'timezone' => $this->timezone,
            'date_format' => $this->date_format,
            'time_format' => $this->time_format,
            'items_per_page' => $this->items_per_page,
            'notifications' => [
                'enabled' => $this->notifications_enabled,
                'email' => $this->email_notifications,
                'push' => $this->push_notifications,
                'in_app' => $this->in_app_notifications,
            ],
            'marketing' => [
                'emails' => $this->marketing_emails,
                'newsletter' => $this->newsletter_subscription,
            ],
            'privacy' => [
                'level' => $this->privacy_level,
                'show_online_status' => $this->show_online_status,
                'show_profile_activity' => $this->show_profile_activity,
                'allow_messages_from' => $this->allow_messages_from,
            ],
            'data_collection_allowed' => $this->data_collection_allowed,
            'content_filter_level' => $this->content_filter_level,
            'custom_settings' => $this->custom_settings,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
