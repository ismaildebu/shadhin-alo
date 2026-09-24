<?php

declare(strict_types=1);

namespace App\Modules\Authorization\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!auth()->user()->hasAnyRole($roles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This action is unauthorized',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
