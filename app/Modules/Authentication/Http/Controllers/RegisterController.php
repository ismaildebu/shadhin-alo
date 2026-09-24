<?php

namespace App\Modules\Authentication\Http\Controllers;

use App\Modules\Authentication\Http\Requests\RegisterRequest;
use App\Modules\Authentication\Http\Resources\UserResource;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\Services\TokenService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    protected AuthenticationService $authService;
    protected TokenService $tokenService;

    public function __construct(
        AuthenticationService $authService,
        TokenService $tokenService
    ) {
        $this->authService = $authService;
        $this->tokenService = $tokenService;
    }

    /**
     * ব্যবহারকারী নিবন্ধন করুন
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        try {
            // ব্যবহারকারী তৈরি করুন
            $user = $this->authService->register($request->validated());

            // টোকেন তৈরি করুন
            $token = $this->tokenService->createToken($user, 'registration');

            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Please verify your email.',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}