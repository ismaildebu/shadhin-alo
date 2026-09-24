<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): \Database\Factories\RoleFactory
    {
        return \Database\Factories\RoleFactory::new();
    }

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
        'is_active',
        'guard_name',
        'sort_order',
    ];

    protected $casts = [
        'is_system' => 'bool',
        'is_active' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'role_id',
            'permission_id'
        )
            ->withTimestamps()
            ->orderBy('name');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Modules\Authentication\Models\User::class,
            'user_role',
            'role_id',
            'user_id'
        )
            ->withTimestamps();
    }

    public static function bySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->first();
    }

    public function isSystemRole(): bool
    {
        return (bool) $this->is_system;
    }

    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    public function assignPermission(Permission $permission): void
    {
        if (!$this->hasPermission($permission)) {
            $this->permissions()->attach($permission->id);
        }
    }

    public function removePermission(Permission $permission): void
    {
        $this->permissions()->detach($permission->id);
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions()
            ->where('permission_id', $permission->id)
            ->exists();
    }

    public function assignPermissions(array $permissions): void
    {
        $permissionIds = Permission::whereIn('slug', $permissions)
            ->pluck('id')
            ->toArray();

        $this->permissions()->sync($permissionIds, false);
    }

    public function revokeAllPermissions(): void
    {
        $this->permissions()->detach();
    }

    public function getPermissionSlugs(): array
    {
        return $this->permissions()->pluck('slug')->toArray();
    }
}