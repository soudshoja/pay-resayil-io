<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgencyActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin doesn't need an agency
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has an agency
        if (!$user->agency) {
            abort(403, __('messages.errors.no_agency'));
        }

        // Check if agency is active
        if (!$user->agency->is_active) {
            abort(403, __('messages.errors.agency_inactive'));
        }

        return $next($request);
    }
}
