<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSalesPerson
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isSalesPerson()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. Sales person privileges required.'
                ], 403);
            }
            abort(403, 'Access denied. Sales person privileges required.');
        }

        return $next($request);
    }
}
