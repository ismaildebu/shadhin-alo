<?php

declare(strict_types=1);

namespace App\Modules\UserManagement\Http\Controllers;

use App\Modules\UserManagement\Models\UserProfile;
use App\Modules\UserManagement\Http\Requests\UpdateProfileRequest;
use App\Modules\UserManagement\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProfileController
{
    public function show(): JsonResponse
    {
        $user = auth()->user();
        $profile = UserProfile::where('user_id', $user->id)->firstOrCreate(
            ['user_id' => $user->id],
            []
        );

        return response()->json([
            'status' => 'success',
            'data' => new ProfileResource($profile->load('user')),
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);

        $profile->update($request->validated());
        $profile->updateLastProfileUpdate();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => new ProfileResource($profile->load('user')),
        ]);
    }

    public function getPublicProfile($userId): JsonResponse
    {
        $profile = UserProfile::where('user_id', $userId)
            ->where('is_public_profile', true)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => new ProfileResource($profile->load('user')),
        ]);
    }

    public function updateAvatar(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);

        if ($request->hasFile('avatar_url')) {
            $path = $request->file('avatar_url')->store('avatars', 'public');
            $profile->avatar_url = url('storage/' . $path);
            $profile->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Avatar updated successfully',
            'data' => new ProfileResource($profile),
        ]);
    }

    public function updateCoverImage(UpdateProfileRequest $request): JsonResponse
    {
        $user = auth()->user();
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);

        if ($request->hasFile('cover_image_url')) {
            $path = $request->file('cover_image_url')->store('covers', 'public');
            $profile->cover_image_url = url('storage/' . $path);
            $profile->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cover image updated successfully',
            'data' => new ProfileResource($profile),
        ]);
    }
}
