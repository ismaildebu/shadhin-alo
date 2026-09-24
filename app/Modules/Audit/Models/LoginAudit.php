<?php

declare(strict_types=1);

namespace App\Modules\Audit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginAudit extends Model
{
    use HasFactory;

    protected $table = 'login_audits';

    protected $fillable = [
        'user_id', 'action', 'email', 'ip_address', 'user_agent',
        'is_successful', 'failure_reason', 'location', 'device_info', 'metadata',
    ];

    protected $casts = [
        'is_successful' => 'bool',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Authentication\Models\User::class);
    }

    public static function logLogin(int $userId, string $email, bool $success, ?string $reason = null): self
    {
        return self::create([
            'user_id' => $userId,
            'action' => 'login',
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_successful' => $success,
            'failure_reason' => $reason,
        ]);
    }

    public static function logLogout(int $userId): self
    {
        return self::create([
            'user_id' => $userId,
            'action' => 'logout',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_successful' => true,
        ]);
    }

    public function getStatusBadge(): string
    {
        return $this->is_successful ? '✅ Success' : '❌ Failed';
    }
}
