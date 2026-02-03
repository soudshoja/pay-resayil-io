<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Agency;
use App\Models\PaymentRequest;

class PlatformController extends Controller
{
    /**
     * Show platform login form
     */
    public function showLogin()
    {
        // If already logged in as platform owner, redirect to dashboard
        if (Auth::check() && Auth::user()->is_platform_owner) {
            return redirect()->route('platform.dashboard');
        }

        return view('platform.login');
    }

    /**
     * Handle platform login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email and check if platform owner
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->is_platform_owner) {
            return back()->withErrors([
                'email' => 'Access denied. Platform owner credentials required.',
            ])->withInput($request->only('email'));
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return redirect()->intended(route('platform.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show platform dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get statistics
        $stats = [
            'agencies' => Agency::count(),
            'users' => User::count(),
            'payments' => PaymentRequest::count(),
            'revenue' => PaymentRequest::where('status', 'paid')->sum('amount'),
        ];

        // Get recent agencies
        $recentAgencies = Agency::withCount('users')
            ->latest()
            ->take(5)
            ->get();

        return view('platform.dashboard', compact('user', 'stats', 'recentAgencies'));
    }

    /**
     * Handle platform logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('platform.login');
    }
}
