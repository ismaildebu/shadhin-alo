<?php

declare(strict_types=1);

namespace App\Modules\Settings\Http\Controllers;

use App\Modules\Settings\Http\Requests\UpdateEmailSettingRequest;
use App\Modules\Settings\Http\Resources\EmailSettingResource;
use App\Modules\Settings\Models\EmailSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class EmailSettingController
{
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', EmailSetting::class);

        $setting = EmailSetting::first() ?? new EmailSetting();

        return response()->json([
            'status' => 'success',
            'data' => new EmailSettingResource($setting),
        ]);
    }

    public function update(UpdateEmailSettingRequest $request): JsonResponse
    {
        Gate::authorize('update', EmailSetting::class);

        $setting = EmailSetting::firstOrCreate([]);
        $setting->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Email settings updated successfully',
            'data' => new EmailSettingResource($setting),
        ]);
    }

    public function test(Request $request): JsonResponse
{
    Gate::authorize('test', EmailSetting::class);

    $validated = $request->validate([
        'email' => ['required', 'email'],
    ]);

    $setting = EmailSetting::first();

    if (!$setting || !$setting->isConfigured()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email settings not configured',
        ], Response::HTTP_BAD_REQUEST);
    }

    try {
        \Mail::raw('Test email from স্বাধীন আলো', function ($message) use ($validated, $setting) {
            $message->to($validated['email'])
                ->from($setting->mail_from_address, $setting->mail_from_name)
                ->subject('Test Email from স্বাধীন আলো');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Test email sent successfully',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to send test email: ' . $e->getMessage(),
        ], Response::HTTP_BAD_REQUEST);
    }
}
}