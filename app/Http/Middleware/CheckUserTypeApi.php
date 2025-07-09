<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserTypeApi
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated. Token required or invalid.'
            ], 401);
        }

        if (!in_array($user->user_type, $roles)) {
            return response()->json([
                'message' => 'Forbidden. Your role is not allowed to access this route.'
            ], 403);
        }

        return $next($request);
    }
}
