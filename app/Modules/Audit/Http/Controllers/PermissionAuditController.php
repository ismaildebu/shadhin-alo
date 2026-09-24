<?php

declare(strict_types=1);

namespace App\Modules\Audit\Http\Controllers;

use App\Modules\Audit\Models\PermissionAudit;
use App\Modules\Audit\Http\Resources\PermissionAuditResource;
use Illuminate\Http\JsonResponse;

class PermissionAuditController
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', PermissionAudit::class);

        $audits = PermissionAudit::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => PermissionAuditResource::collection($audits),
        ]);
    }

    public function denied(): JsonResponse
    {
        $audits = PermissionAudit::where('is_allowed', false)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => PermissionAuditResource::collection($audits),
        ]);
    }

    public function byUser(int $userId): JsonResponse
    {
        $audits = PermissionAudit::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => PermissionAuditResource::collection($audits),
        ]);
    }

    public function summary(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_checks' => PermissionAudit::count(),
                'allowed' => PermissionAudit::where('is_allowed', true)->count(),
                'denied' => PermissionAudit::where('is_allowed', false)->count(),
                'most_denied_permissions' => PermissionAudit::selectRaw('permission, COUNT(*) as count')
                    ->where('is_allowed', false)
                    ->groupBy('permission')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->pluck('count', 'permission')
                    ->toArray(),
            ],
        ]);
    }
}
