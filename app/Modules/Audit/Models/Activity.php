<?php

declare(strict_types=1);

namespace App\Modules\Audit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id', 'model_name',
        'description', 'changes', 'old_values', 'new_values',
        'ip_address', 'user_agent', 'method', 'url', 'status_code',
        'response_time_ms', 'metadata',
    ];

    protected $casts = [
        'changes' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'response_time_ms' => 'int',
        'status_code' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Authentication\Models\User::class);
    }

    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $changes = null,
        ?string $description = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'model_name' => $modelType ? class_basename($modelType) : null,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->path(),
        ]);
    }

    public function getChangeSummary(): string
    {
        if (!$this->changes) {
            return 'No changes';
        }
        $summaries = [];
        foreach ($this->changes as $field => $change) {
            $old = $change['old'] ?? 'null';
            $new = $change['new'] ?? 'null';
            $summaries[] = "{$field}: {$old} → {$new}";
        }
        return implode(', ', array_slice($summaries, 0, 3));
    }

    public function isCreated(): bool { return $this->action === 'created'; }
    public function isUpdated(): bool { return $this->action === 'updated'; }
    public function isDeleted(): bool { return $this->action === 'deleted'; }
}
