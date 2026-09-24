<?php

namespace App\Modules\Authentication\Http\Controllers;

use App\Modules\Authentication\Http\Requests\PasswordResetRequest;
use App\Modules\Authentication\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    protected PasswordResetService $resetService;

    public function __construct(PasswordResetService $resetService)
    {
        $this->resetService = $resetService;
    }

    /**
     * পাসওয়ার্ড রিসেট অনুরোধ করুন
     */
    public function requestReset(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc,dns'],
        ]);

        try {
            $this->resetService->requestReset($request->email);

            return response()->json([
                'success' => true,
                'message' => 'If an account exists with that email, a password reset link will be sent.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process reset request: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * টোকেন যাচাই করুন
     */
    public function verifyToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'token' => ['required', 'string'],
        ]);

        try {
            $this->resetService->verifyToken($request->email, $request->token);

            return response()->json([
                'success' => true,
                'message' => 'Token is valid',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * পাসওয়ার্ড রিসেট করুন
     */
    public function reset(PasswordResetRequest $request): JsonResponse
    {
        try {
            $user = $this->resetService->complete(
                $request->email,
                $request->token,
                $request->password
            );

            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully',
                'data' => ['email' => $user->email],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}