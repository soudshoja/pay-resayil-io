<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAccountant()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. Accountant privileges required.'
                ], 403);
            }
            abort(403, 'Access denied. Accountant privileges required.');
        }

        return $next($request);
    }
}
