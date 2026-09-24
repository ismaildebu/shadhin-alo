<?php

declare(strict_types=1);

namespace App\Modules\Audit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionAudit extends Model
{
    use HasFactory;

    protected $table = 'permission_audits';

    protected $fillable = [
        'user_id', 'permission', 'model_type', 'model_id', 'is_allowed',
        'ip_address', 'user_agent', 'url', 'metadata',
    ];

    protected $casts = [
        'is_allowed' => 'bool',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Authentication\Models\User::class);
    }

    public static function log(string $permission, bool $allowed, ?string $model = null, ?int $modelId = null): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'permission' => $permission,
            'model_type' => $model,
            'model_id' => $modelId,
            'is_allowed' => $allowed,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->path(),
        ]);
    }
}
