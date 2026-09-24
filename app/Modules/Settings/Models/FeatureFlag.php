<?php

declare(strict_types=1);

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureFlag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'feature_flags';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_enabled',
        'rollout_percentage',
        'target_users',
        'target_roles',
        'metadata',
    ];

    protected $casts = [
        'is_enabled' => 'bool',
        'target_users' => 'array',
        'target_roles' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function isEnabled(string $slug): bool
    {
        $flag = self::where('slug', $slug)->first();
        return $flag ? $flag->is_enabled : false;
    }

    public function isEnabledForUser($userId): bool
    {
        if (!$this->is_enabled) {
            return false;
        }

        if (!empty($this->target_users) && !in_array($userId, $this->target_users)) {
            return false;
        }

        if ($this->rollout_percentage < 100) {
            $hash = crc32($userId . $this->slug);
            return ($hash % 100) < $this->rollout_percentage;
        }

        return true;
    }

    public function isEnabledForRole(string $role): bool
    {
        if (!$this->is_enabled) {
            return false;
        }

        if (!empty($this->target_roles) && !in_array($role, $this->target_roles)) {
            return false;
        }

        return true;
    }
}
