<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Models;

use App\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotificationPreference extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_notification_preferences';

    protected $fillable = [
        'user_id',
        'notification_type',
        'email_enabled',
        'push_enabled',
        'in_app_enabled',
        'sms_enabled',
        'frequency',
        'quiet_hours_enabled',
        'quiet_hours_start',
        'quiet_hours_end',
        'timezone',
    ];

    protected $casts = [
        'email_enabled' => 'bool',
        'push_enabled' => 'bool',
        'in_app_enabled' => 'bool',
        'sms_enabled' => 'bool',
        'quiet_hours_enabled' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isEnabled(): bool
    {
        return $this->email_enabled || $this->push_enabled || $this->in_app_enabled || $this->sms_enabled;
    }

    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_enabled) {
            return false;
        }

        $now = now()->setTimezone($this->timezone);
        $currentTime = $now->format('H:i');

        $start = $this->quiet_hours_start;
        $end = $this->quiet_hours_end;

        if ($start < $end) {
            return $currentTime >= $start && $currentTime < $end;
        }

        return $currentTime >= $start || $currentTime < $end;
    }
}
