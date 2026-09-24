<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Models;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPreference extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_preferences';

    protected $fillable = [
        'user_id',
        'theme',
        'language',
        'timezone',
        'date_format',
        'time_format',
        'items_per_page',
        'notifications_enabled',
        'email_notifications',
        'push_notifications',
        'in_app_notifications',
        'marketing_emails',
        'newsletter_subscription',
        'privacy_level',
        'show_online_status',
        'show_profile_activity',
        'data_collection_allowed',
        'allow_messages_from',
        'content_filter_level',
        'custom_settings',
    ];

    protected $casts = [
        'notifications_enabled' => 'bool',
        'email_notifications' => 'bool',
        'push_notifications' => 'bool',
        'in_app_notifications' => 'bool',
        'marketing_emails' => 'bool',
        'newsletter_subscription' => 'bool',
        'show_online_status' => 'bool',
        'show_profile_activity' => 'bool',
        'data_collection_allowed' => 'bool',
        'custom_settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTheme(): string
    {
        return $this->theme ?? 'light';
    }

    public function getLanguage(): string
    {
        return $this->language ?? 'en';
    }

    public function getTimezone(): string
    {
        return $this->timezone ?? 'UTC';
    }

    public function isNotificationsEnabled(): bool
    {
        return (bool) $this->notifications_enabled;
    }

    public function canReceiveEmails(): bool
    {
        return $this->notifications_enabled && $this->email_notifications;
    }

    public function canReceivePushNotifications(): bool
    {
        return $this->notifications_enabled && $this->push_notifications;
    }

    public function shouldReceiveMarketingEmails(): bool
    {
        return (bool) $this->marketing_emails;
    }

    public function isNewsletterSubscribed(): bool
    {
        return (bool) $this->newsletter_subscription;
    }
}
