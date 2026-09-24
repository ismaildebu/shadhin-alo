<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Controllers;

use App\Modules\Settings\Models\FeatureFlag;
use App\Modules\Settings\Http\Requests\UpdateFeatureFlagRequest;
use App\Modules\Settings\Http\Resources\FeatureFlagResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class FeatureFlagController
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', FeatureFlag::class);

        $flags = FeatureFlag::orderBy('slug')->get();

        return response()->json([
            'status' => 'success',
            'data' => FeatureFlagResource::collection($flags),
        ]);
    }

    public function store(UpdateFeatureFlagRequest $request): JsonResponse
    {
        $this->authorize('create', FeatureFlag::class);

        $flag = FeatureFlag::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Feature flag created successfully',
            'data' => new FeatureFlagResource($flag),
        ], Response::HTTP_CREATED);
    }

    public function show(FeatureFlag $flag): JsonResponse
    {
        $this->authorize('view', $flag);

        return response()->json([
            'status' => 'success',
            'data' => new FeatureFlagResource($flag),
        ]);
    }

    public function update(UpdateFeatureFlagRequest $request, FeatureFlag $flag): JsonResponse
    {
        $this->authorize('update', $flag);

        $flag->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Feature flag updated successfully',
            'data' => new FeatureFlagResource($flag),
        ]);
    }

    public function toggle(FeatureFlag $flag): JsonResponse
    {
        $this->authorize('update', $flag);

        $flag->is_enabled = !$flag->is_enabled;
        $flag->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Feature flag toggled successfully',
            'data' => new FeatureFlagResource($flag),
        ]);
    }

    public function isEnabled(string $slug): JsonResponse
    {
        $enabled = FeatureFlag::isEnabled($slug);

        return response()->json([
            'status' => 'success',
            'slug' => $slug,
            'enabled' => $enabled,
        ]);
    }
}
