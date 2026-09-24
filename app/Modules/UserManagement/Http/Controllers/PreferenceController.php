<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Http\Controllers;

use App\Modules\UserManagement\Models\UserPreference;
use App\Modules\UserManagement\Models\UserNotificationPreference;
use App\Modules\UserManagement\Http\Requests\UpdatePreferenceRequest;
use App\Modules\UserManagement\Http\Resources\PreferenceResource;
use App\Modules\UserManagement\Http\Resources\NotificationPreferenceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PreferenceController
{
    public function show(): JsonResponse
    {
        $user = auth()->user();
        $preference = UserPreference::firstOrCreate(['user_id' => $user->id]);

        return response()->json([
            'status' => 'success',
            'data' => new PreferenceResource($preference),
        ]);
    }

    public function update(UpdatePreferenceRequest $request): JsonResponse
    {
        $user = auth()->user();
        $preference = UserPreference::firstOrCreate(['user_id' => $user->id]);

        $preference->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Preferences updated successfully',
            'data' => new PreferenceResource($preference),
        ]);
    }

    public function updateTheme(UpdatePreferenceRequest $request): JsonResponse
    {
        $user = auth()->user();
        $preference = UserPreference::firstOrCreate(['user_id' => $user->id]);

        $preference->update(['theme' => $request->input('theme', 'light')]);

        return response()->json([
            'status' => 'success',
            'message' => 'Theme updated successfully',
            'data' => new PreferenceResource($preference),
        ]);
    }

    public function updateLanguage(UpdatePreferenceRequest $request): JsonResponse
    {
        $user = auth()->user();
        $preference = UserPreference::firstOrCreate(['user_id' => $user->id]);

        $preference->update(['language' => $request->input('language', 'en')]);

        return response()->json([
            'status' => 'success',
            'message' => 'Language updated successfully',
            'data' => new PreferenceResource($preference),
        ]);
    }

    public function getNotificationPreferences(): JsonResponse
    {
        $user = auth()->user();
        $preferences = UserNotificationPreference::where('user_id', $user->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => NotificationPreferenceResource::collection($preferences),
        ]);
    }

    public function updateNotificationPreference(UpdatePreferenceRequest $request, string $notificationType): JsonResponse
    {
        $user = auth()->user();
        $preference = UserNotificationPreference::updateOrCreate(
            ['user_id' => $user->id, 'notification_type' => $notificationType],
            $request->validated()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Notification preference updated successfully',
            'data' => new NotificationPreferenceResource($preference),
        ]);
    }

    public function disableNotifications(string $notificationType): JsonResponse
    {
        $user = auth()->user();
        UserNotificationPreference::where('user_id', $user->id)
            ->where('notification_type', $notificationType)
            ->update([
                'email_enabled' => false,
                'push_enabled' => false,
                'in_app_enabled' => false,
                'sms_enabled' => false,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifications disabled successfully',
        ]);
    }
}
