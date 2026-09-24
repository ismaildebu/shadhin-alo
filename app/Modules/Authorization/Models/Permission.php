<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
        'is_system',
        'guard_name',
    ];

    protected $casts = [
        'is_system' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission',
            'permission_id',
            'role_id'
        )
            ->withTimestamps();
    }

    public static function bySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->first();
    }

    public static function byModule(string $module): array
    {
        return self::where('module', $module)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getFullName(): string
    {
        return "{$this->module}.{$this->slug}";
    }
}
