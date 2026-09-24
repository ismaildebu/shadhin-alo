<?php

declare(strict_types=1);

namespace App\Modules\Audit\Http\Controllers;

use App\Modules\Audit\Models\LoginAudit;
use App\Modules\Audit\Http\Resources\LoginAuditResource;
use Illuminate\Http\JsonResponse;

class LoginAuditController
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', LoginAudit::class);

        $audits = LoginAudit::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => LoginAuditResource::collection($audits),
            'pagination' => [
                'total' => $audits->total(),
                'per_page' => $audits->perPage(),
                'current_page' => $audits->currentPage(),
            ],
        ]);
    }

    public function byUser(int $userId): JsonResponse
    {
        $audits = LoginAudit::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => LoginAuditResource::collection($audits),
        ]);
    }

    public function failed(): JsonResponse
    {
        $audits = LoginAudit::where('is_successful', false)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => LoginAuditResource::collection($audits),
        ]);
    }

    public function suspiciousActivity(): JsonResponse
    {
        // Failed logins from same IP multiple times
        $suspicious = LoginAudit::selectRaw('ip_address, COUNT(*) as attempts')
            ->where('is_successful', false)
            ->where('created_at', '>=', now()->subDays(1))
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) > 5')
            ->pluck('attempts', 'ip_address');

        return response()->json([
            'status' => 'success',
            'data' => [
                'suspicious_ips' => $suspicious,
                'total_suspicious_ips' => $suspicious->count(),
            ],
        ]);
    }

    public function summary(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_logins' => LoginAudit::count(),
                'today' => LoginAudit::whereDate('created_at', today())->count(),
                'successful' => LoginAudit::where('is_successful', true)->count(),
                'failed' => LoginAudit::where('is_successful', false)->count(),
                'failed_last_24h' => LoginAudit::where('is_successful', false)
                    ->where('created_at', '>=', now()->subDay())
                    ->count(),
            ],
        ]);
    }
}
