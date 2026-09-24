<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Http\Controllers;

use App\Modules\Authorization\Models\Permission;
use App\Modules\Authorization\Http\Resources\PermissionResource;
use Illuminate\Http\JsonResponse;

class PermissionController
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Permission::class);

        $permissions = Permission::orderBy('module')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => PermissionResource::collection($permissions),
        ]);
    }

    public function byModule(string $module): JsonResponse
    {
        $permissions = Permission::where('module', $module)
            ->orderBy('name')
            ->get();

        if ($permissions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No permissions found for this module',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => PermissionResource::collection($permissions),
        ]);
    }

    public function show(Permission $permission): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => new PermissionResource($permission->load('roles')),
        ]);
    }

    public function groupedByModule(): JsonResponse
    {
        $permissions = Permission::orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        return response()->json([
            'status' => 'success',
            'data' => $permissions,
        ]);
    }
}
