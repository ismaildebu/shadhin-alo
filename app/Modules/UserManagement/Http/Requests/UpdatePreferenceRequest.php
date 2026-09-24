<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme' => ['sometimes', 'in:light,dark,auto'],
            'language' => ['sometimes', 'string', 'size:5'],
            'timezone' => ['sometimes', 'timezone'],
            'date_format' => ['sometimes', 'string', 'max:20'],
            'time_format' => ['sometimes', 'string', 'max:20'],
            'items_per_page' => ['sometimes', 'integer', 'min:5', 'max:100'],
            'notifications_enabled' => ['sometimes', 'boolean'],
            'email_notifications' => ['sometimes', 'boolean'],
            'push_notifications' => ['sometimes', 'boolean'],
            'in_app_notifications' => ['sometimes', 'boolean'],
            'marketing_emails' => ['sometimes', 'boolean'],
            'newsletter_subscription' => ['sometimes', 'boolean'],
            'privacy_level' => ['sometimes', 'in:public,friends,private'],
            'show_online_status' => ['sometimes', 'boolean'],
            'show_profile_activity' => ['sometimes', 'boolean'],
            'data_collection_allowed' => ['sometimes', 'boolean'],
            'allow_messages_from' => ['sometimes', 'in:everyone,friends,none'],
            'content_filter_level' => ['sometimes', 'in:none,moderate,strict'],
            'custom_settings' => ['sometimes', 'array'],
            'email_enabled' => ['sometimes', 'boolean'],
            'push_enabled' => ['sometimes', 'boolean'],
            'sms_enabled' => ['sometimes', 'boolean'],
            'frequency' => ['sometimes', 'in:instant,daily,weekly,monthly'],
            'quiet_hours_enabled' => ['sometimes', 'boolean'],
            'quiet_hours_start' => ['sometimes', 'date_format:H:i:s'],
            'quiet_hours_end' => ['sometimes', 'date_format:H:i:s'],
        ];
    }
}
