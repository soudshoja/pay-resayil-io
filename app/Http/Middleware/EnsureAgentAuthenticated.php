<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgentAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::get('agent_authenticated') || !Session::get('agent_id')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('agent.login');
        }

        return $next($request);
    }
}
