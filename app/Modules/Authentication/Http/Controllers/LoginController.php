<?php

namespace App\Modules\Authentication\Http\Controllers;

use App\Modules\Authentication\Http\Requests\LoginRequest;
use App\Modules\Authentication\Http\Resources\UserResource;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\Services\TokenService;
use App\Modules\Authentication\Exceptions\InvalidCredentialsException;
use App\Modules\Authentication\Exceptions\EmailNotVerifiedException;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
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
     * ব্যবহারকারী লগইন করুন
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->ensureIsNotRateLimited();

        try {
            $user = $this->authService->login(
                $request->email,
                $request->password,
                $request->remember_me ?? false
            );

            // টোকেন তৈরি করুন
            $token = $this->tokenService->createToken($user, 'login');

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
            ], 200);
        } catch (InvalidCredentialsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        } catch (EmailNotVerifiedException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'email_verified' => false,
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ব্যবহারকারী লজআউট করুন
     */
    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout(auth()->user());

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}