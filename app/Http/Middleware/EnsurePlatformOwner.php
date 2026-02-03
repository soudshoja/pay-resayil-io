<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformOwner
{
    /**
     * Handle an incoming request.
     *
     * Ensure the authenticated user is a platform owner.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->is_platform_owner) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. Platform owner privileges required.'
                ], 403);
            }

            return redirect()->route('platform.login')
                ->with('error', 'Access denied. Platform owner privileges required.');
        }

        return $next($request);
    }
}
