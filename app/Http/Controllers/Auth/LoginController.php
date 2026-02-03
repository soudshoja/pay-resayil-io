<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        Auth::login($result['user']);

        return redirect()->intended(route('dashboard'))
            ->with('success', __('messages.auth.welcome_back', [
                'name' => $result['user']->full_name
            ]));
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
