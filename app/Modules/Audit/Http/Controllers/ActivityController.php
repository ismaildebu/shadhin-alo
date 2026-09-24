<?php

declare(strict_types=1);

namespace App\Modules\Audit\Http\Controllers;

use App\Modules\Audit\Models\Activity;
use App\Modules\Audit\Http\Resources\ActivityResource;
use Illuminate\Http\JsonResponse;

class ActivityController
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Activity::class);

        $activities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return response()->json([
            'status' => 'success',
            'data' => ActivityResource::collection($activities),
            'pagination' => [
                'total' => $activities->total(),
                'per_page' => $activities->perPage(),
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
            ],
        ]);
    }

    public function byUser(int $userId): JsonResponse
    {
        $activities = Activity::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return response()->json([
            'status' => 'success',
            'data' => ActivityResource::collection($activities),
        ]);
    }

    public function byModel(string $modelType, int $modelId): JsonResponse
    {
        $activities = Activity::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ActivityResource::collection($activities),
        ]);
    }

    public function show(Activity $activity): JsonResponse
    {
        $this->authorize('view', $activity);

        return response()->json([
            'status' => 'success',
            'data' => new ActivityResource($activity),
        ]);
    }

    public function summary(): JsonResponse
    {
        $summary = [
            'total_activities' => Activity::count(),
            'today' => Activity::whereDate('created_at', today())->count(),
            'this_week' => Activity::whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'this_month' => Activity::whereBetween('created_at', [now()->startOfMonth(), now()])->count(),
            'by_action' => Activity::selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action')
                ->toArray(),
            'by_model' => Activity::selectRaw('model_name, COUNT(*) as count')
                ->whereNotNull('model_name')
                ->groupBy('model_name')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'model_name')
                ->toArray(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $summary,
        ]);
    }

    public function export(): JsonResponse
    {
        $this->authorize('export', Activity::class);

        $activities = Activity::with('user')->get();

        $csv = "Time,User,Action,Model,ID,Description,IP Address,Method,URL\n";
        foreach ($activities as $activity) {
            $csv .= "{$activity->created_at},"
                . "{$activity->user?->email},"
                . "{$activity->action},"
                . "{$activity->model_name},"
                . "{$activity->model_id},"
                . "\"{$activity->description}\","
                . "{$activity->ip_address},"
                . "{$activity->method},"
                . "{$activity->url}\n";
        }

        return response()->json([
            'status' => 'success',
            'csv' => $csv,
            'filename' => 'activities-' . now()->format('Y-m-d') . '.csv',
        ]);
    }
}
