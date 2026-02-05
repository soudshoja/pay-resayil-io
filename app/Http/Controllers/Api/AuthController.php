<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OTPService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private OTPService $otpService
    ) {}

    /**
     * Send OTP to mobile number
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string|min:8|max:20',
            'purpose' => 'in:login,registration,verification',
        ]);

        $result = $this->otpService->sendOTP(
            mobileNumber: $request->mobile_number,
            purpose: $request->input('purpose', 'login')
        );

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'otp_code' => 'required|string|size:6',
            'purpose' => 'in:login,registration,verification',
        ]);

        $result = $this->otpService->verifyOTP(
            mobileNumber: $request->mobile_number,
            otpCode: $request->otp_code,
            purpose: $request->input('purpose', 'login')
        );

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
        ], $result['success'] ? 200 : 400);
    }
}
