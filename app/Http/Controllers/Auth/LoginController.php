<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct(
        private OTPService $otpService
    ) {}

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login with email and password (for internal staff)
     */
    public function loginWithEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => __('messages.auth.user_not_found')
            ])->withInput($request->only('email'));
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => __('messages.auth.user_not_found')
            ])->withInput($request->only('email'));
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => __('messages.auth.invalid_credentials', [], 'en') ?: 'Invalid email or password'
            ])->withInput($request->only('email'));
        }

        // Log the user in
        Auth::login($user, $request->boolean('remember'));

        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Redirect based on role
        $redirectRoute = $this->getRedirectRouteForRole($user);

        return redirect()->intended($redirectRoute)
            ->with('success', __('messages.auth.welcome_back', [
                'name' => $user->full_name
            ]));
    }

    /**
     * Send OTP to phone number
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:8|max:20',
        ]);

        $result = $this->otpService->sendOTP(
            mobileNumber: $request->phone,
            purpose: 'login'
        );

        if (!$result['success']) {
            return back()->withErrors([
                'phone' => $result['message']
            ])->withInput();
        }

        return redirect()->route('verify-otp.show', [
            'phone' => $request->phone
        ])->with('success', $result['message']);
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOTPForm(Request $request)
    {
        $phone = $request->query('phone');

        if (!$phone) {
            return redirect()->route('login');
        }

        return view('auth.verify-otp', compact('phone'));
    }

    /**
     * Verify OTP and login
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $result = $this->otpService->loginWithOTP(
            mobileNumber: $request->phone,
            otpCode: $request->otp
        );

        if (!$result['success']) {
            return back()->withErrors([
                'otp' => $result['message']
            ])->withInput();
        }

        // Log the user in
        $user = $result['user'];
        Auth::login($user);

        // Redirect based on role
        $redirectRoute = $this->getRedirectRouteForRole($user);

        return redirect()->intended($redirectRoute)
            ->with('success', __('messages.auth.welcome_back', [
                'name' => $user->full_name
            ]));
    }

    /**
     * Get redirect route based on user role
     */
    protected function getRedirectRouteForRole($user): string
    {
        if ($user->isPlatformOwner()) {
            return route('platform.dashboard');
        }

        if ($user->isClientAdmin()) {
            return route('client.dashboard');
        }

        if ($user->isSalesPerson()) {
            return route('sales.dashboard');
        }

        if ($user->isAccountant()) {
            return route('accountant.dashboard');
        }

        // Default fallback to legacy dashboard
        return route('dashboard');
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $result = $this->otpService->sendOTP(
            mobileNumber: $request->phone,
            purpose: 'login'
        );

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->withErrors([
                'otp' => $result['message']
            ]);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', __('messages.auth.logged_out'));
    }
}
