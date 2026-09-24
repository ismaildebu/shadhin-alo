<?php

namespace App\Modules\Authentication\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmail
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('authentication.email_verification_required') 
            && auth()->check() 
            && !auth()->user()->isVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification required',
                'email_verified' => false,
            ], 403);
        }

        return $next($request);
    }
}