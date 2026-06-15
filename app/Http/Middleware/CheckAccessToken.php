<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Ensure token is not expired
        $currentToken = $user->currentAccessToken();
        if ($currentToken && $currentToken->expires_at && $currentToken->expires_at->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access token has expired.'
            ], 401);
        }

        // Ensure the token has 'access-api' ability
        if (!$user->tokenCan('access-api')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid untuk mengakses data ini. Silakan gunakan access token.'
            ], 403);
        }

        return $next($request);
    }
}
