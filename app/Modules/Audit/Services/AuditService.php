<?php

declare(strict_types=1);

namespace App\Modules\Audit\Services;

use App\Modules\Audit\Models\Activity;
use App\Modules\Audit\Models\LoginAudit;
use App\Modules\Audit\Models\PermissionAudit;

class AuditService
{
    public function logActivity(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $changes = null,
        ?string $description = null
    ): Activity {
        return Activity::log($action, $modelType, $modelId, $changes, $description);
    }

    public function logLogin(int $userId, string $email, bool $success, ?string $reason = null): LoginAudit
    {
        return LoginAudit::logLogin($userId, $email, $success, $reason);
    }

    public function logLogout(int $userId): LoginAudit
    {
        return LoginAudit::logLogout($userId);
    }

    public function logPermission(string $permission, bool $allowed, ?string $model = null, ?int $modelId = null): PermissionAudit
    {
        return PermissionAudit::log($permission, $allowed, $model, $modelId);
    }

    public function getActivitySummary(int $days = 30): array
    {
        return [
            'period_days' => $days,
            'total_activities' => Activity::whereBetween('created_at', [now()->subDays($days), now()])->count(),
            'by_action' => Activity::selectRaw('action, COUNT(*) as count')
                ->whereBetween('created_at', [now()->subDays($days), now()])
                ->groupBy('action')
                ->pluck('count', 'action')
                ->toArray(),
            'by_model' => Activity::selectRaw('model_name, COUNT(*) as count')
                ->whereBetween('created_at', [now()->subDays($days), now()])
                ->whereNotNull('model_name')
                ->groupBy('model_name')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'model_name')
                ->toArray(),
        ];
    }

    public function cleanupOldAuditLogs(int $daysToKeep = 90): array
    {
        $cutoffDate = now()->subDays($daysToKeep);

        return [
            'activities_deleted' => Activity::where('created_at', '<', $cutoffDate)->delete(),
            'login_audits_deleted' => LoginAudit::where('created_at', '<', $cutoffDate)->delete(),
            'permission_audits_deleted' => PermissionAudit::where('created_at', '<', $cutoffDate)->delete(),
        ];
    }

    public function getUserActivityStats(int $userId, int $days = 30): array
    {
        $startDate = now()->subDays($days);

        return [
            'user_id' => $userId,
            'period_days' => $days,
            'total_activities' => Activity::where('user_id', $userId)
                ->where('created_at', '>=', $startDate)->count(),
            'total_logins' => LoginAudit::where('user_id', $userId)
                ->where('created_at', '>=', $startDate)->count(),
            'failed_logins' => LoginAudit::where('user_id', $userId)
                ->where('is_successful', false)
                ->where('created_at', '>=', $startDate)->count(),
            'permission_denied' => PermissionAudit::where('user_id', $userId)
                ->where('is_allowed', false)
                ->where('created_at', '>=', $startDate)->count(),
        ];
    }
}
