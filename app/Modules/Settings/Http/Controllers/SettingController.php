<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Controllers;

use App\Modules\Settings\Models\Setting;
use App\Modules\Settings\Http\Requests\UpdateSettingRequest;
use App\Modules\Settings\Http\Resources\SettingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SettingController
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Setting::class);

        $settings = Setting::where('is_public', true)
            ->orderBy('module')
            ->orderBy('key')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => SettingResource::collection($settings),
        ]);
    }

    public function byModule(string $module): JsonResponse
    {
        $settings = Setting::where('module', $module)
            ->where('is_public', true)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => SettingResource::collection($settings),
        ]);
    }

    public function show(Setting $setting): JsonResponse
    {
        $this->authorize('view', $setting);

        return response()->json([
            'status' => 'success',
            'data' => new SettingResource($setting),
        ]);
    }

    public function update(UpdateSettingRequest $request, Setting $setting): JsonResponse
    {
        $this->authorize('update', $setting);

        $setting->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Setting updated successfully',
            'data' => new SettingResource($setting),
        ]);
    }

    public function getSystemSettings(): JsonResponse
    {
        $settings = Setting::where('module', 'system')
            ->where('is_public', true)
            ->pluck('value', 'key');

        return response()->json([
            'status' => 'success',
            'data' => $settings,
        ]);
    }
}
