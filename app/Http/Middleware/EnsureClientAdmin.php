<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isClientAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. Client admin privileges required.'
                ], 403);
            }
            abort(403, 'Access denied. Client admin privileges required.');
        }

        return $next($request);
    }
}
