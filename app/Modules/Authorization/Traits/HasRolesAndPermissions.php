<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Traits;

use App\Modules\Authorization\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRolesAndPermissions
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_role',
            'user_id',
            'role_id'
        )
            ->withTimestamps();
    }

    public function assignRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::bySlug($role);
        }

        if ($role && !$this->hasRole($role)) {
            $this->roles()->attach($role->id);
        }
    }

    public function removeRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::bySlug($role);
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    public function hasRole(string|Role $role): bool
    {
        if (is_string($role)) {
            return $this->roles()->where('slug', $role)->exists();
        }

        return $this->roles()->where('role_id', $role->id)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('slug', $roles)
            ->exists();
    }

    public function hasAllRoles(array $roles): bool
    {
        return collect($roles)
            ->every(fn($role) => $this->hasRole($role));
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', fn($q) => $q->where('slug', $permission))
            ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', fn($q) => $q->whereIn('slug', $permissions))
            ->exists();
    }

    public function hasAllPermissions(array $permissions): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        return collect($permissions)
            ->every(fn($permission) => $this->hasPermission($permission));
    }

    public function getPermissions(): array
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->flatMap(fn($role) => $role->permissions->pluck('slug'))
            ->unique()
            ->toArray();
    }

    public function getRoles(): array
    {
        return $this->roles()->pluck('slug')->toArray();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }
}
