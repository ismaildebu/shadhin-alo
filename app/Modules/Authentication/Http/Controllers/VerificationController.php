<?php

namespace App\Modules\Authentication\Http\Controllers;

use App\Modules\Authentication\Models\User;
use App\Modules\Authentication\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * ইমেইল যাচাই করুন
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        try {
            $user = User::where('email_verification_token', $request->token)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification token',
                ], 400);
            }

            $this->authService->verifyEmail($user);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * যাচাইকরণ ইমেইল পুনরায় পাঠান
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc,dns'],
        ]);

        try {
            $user = User::where('email', $request->email)
                ->whereNull('email_verified_at')
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or already verified',
                ], 404);
            }

            // নতুন টোকেন তৈরি করুন
            $user->update([
                'email_verification_token' => str()->random(60),
                'email_verification_expires_at' => now()->addDays(7),
            ]);

            // ইভেন্ট ফায়ার করুন (ইমেইল পাঠানোর জন্য)
            event(new \App\Modules\Authentication\Events\UserRegistered($user));

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend verification email: ' . $e->getMessage(),
            ], 500);
        }
    }
}