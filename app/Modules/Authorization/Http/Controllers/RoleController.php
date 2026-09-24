<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Http\Controllers;

use App\Modules\Authorization\Models\Role;
use App\Modules\Authorization\Http\Requests\StoreRoleRequest;
use App\Modules\Authorization\Http\Requests\UpdateRoleRequest;
use App\Modules\Authorization\Http\Resources\RoleResource;
use App\Modules\Authorization\Http\Requests\AssignRolePermissionsRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoleController
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::with('permissions')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => RoleResource::collection($roles),
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $this->authorize('create', Role::class);

        $role = Role::create($request->validated());

        if ($request->has('permissions')) {
            $role->assignPermissions($request->input('permissions', []));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'data' => new RoleResource($role),
        ], Response::HTTP_CREATED);
    }

    public function show(Role $role): JsonResponse
    {
        $this->authorize('view', $role);

        return response()->json([
            'status' => 'success',
            'data' => new RoleResource($role->load('permissions')),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $this->authorize('update', $role);

        if ($role->is_system) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot modify system roles',
            ], Response::HTTP_FORBIDDEN);
        }

        $role->update($request->validated());

        if ($request->has('permissions')) {
            $role->permissions()->sync(
                \App\Modules\Authorization\Models\Permission::whereIn(
                    'slug',
                    $request->input('permissions', [])
                )
                    ->pluck('id')
                    ->toArray()
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'data' => new RoleResource($role->load('permissions')),
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        if ($role->is_system) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete system roles',
            ], Response::HTTP_FORBIDDEN);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete role with assigned users',
            ], Response::HTTP_CONFLICT);
        }

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully',
        ]);
    }

   
    public function assignPermissions(
    Role $role,
    AssignRolePermissionsRequest $request
): JsonResponse
    {
        $this->authorize('update', $role);

        $role->assignPermissions($request->input('permissions', []));

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions assigned successfully',
            'data' => new RoleResource($role->load('permissions')),
        ]);
    }
}