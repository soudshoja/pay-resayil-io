<?php

namespace App\Services;

use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OTPService
{
    private ResayilWhatsAppService $whatsappService;

    public function __construct(ResayilWhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send OTP to mobile number
     */
    public function sendOTP(
        string $mobileNumber,
        string $purpose = 'login',
        ?int $agencyId = null
    ): array {
        // Normalize phone number
        $mobileNumber = $this->normalizePhoneNumber($mobileNumber);

        // Check cooldown
        if (!OtpVerification::canResend($mobileNumber)) {
            $seconds = OtpVerification::secondsUntilCanResend($mobileNumber);
            return [
                'success' => false,
                'error' => 'cooldown',
                'seconds_remaining' => $seconds,
                'message' => __('messages.otp.cooldown', ['seconds' => $seconds])
            ];
        }

        // Generate OTP
        $otp = OtpVerification::generateFor(
            mobileNumber: $mobileNumber,
            purpose: $purpose,
            ipAddress: request()->ip()
        );

        // Send via WhatsApp
        $result = $this->whatsappService->sendOTP(
            phoneNumber: $mobileNumber,
            otpCode: $otp->otp_code,
            validityMinutes: config('services.otp.expiry_minutes', 10),
            agencyId: $agencyId
        );

        if (!$result['success']) {
            Log::error('OTP send failed', [
                'mobile' => $mobileNumber,
                'error' => $result['error'] ?? 'Unknown'
            ]);

            return [
                'success' => false,
                'error' => 'send_failed',
                'message' => __('messages.otp.send_failed')
            ];
        }

        return [
            'success' => true,
            'message' => __('messages.otp.sent'),
            'expires_in' => config('services.otp.expiry_minutes', 10) * 60
        ];
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP(
        string $mobileNumber,
        string $otpCode,
        string $purpose = 'login'
    ): array {
        $mobileNumber = $this->normalizePhoneNumber($mobileNumber);

        $otp = OtpVerification::verify(
            mobileNumber: $mobileNumber,
            otpCode: $otpCode,
            purpose: $purpose
        );

        if (!$otp) {
            // Check if max attempts exceeded
            $lastOtp = OtpVerification::where('mobile_number', $mobileNumber)
                ->where('purpose', $purpose)
                ->latest()
                ->first();

            if ($lastOtp && $lastOtp->hasExceededAttempts()) {
                return [
                    'success' => false,
                    'error' => 'max_attempts',
                    'message' => __('messages.otp.max_attempts')
                ];
            }

            if ($lastOtp && $lastOtp->isExpired()) {
                return [
                    'success' => false,
                    'error' => 'expired',
                    'message' => __('messages.otp.expired')
                ];
            }

            return [
                'success' => false,
                'error' => 'invalid',
                'message' => __('messages.otp.invalid')
            ];
        }

        return [
            'success' => true,
            'message' => __('messages.otp.verified'),
            'otp' => $otp
        ];
    }

    /**
     * Login with OTP (find or create user)
     */
    public function loginWithOTP(
        string $mobileNumber,
        string $otpCode
    ): array {
        $verification = $this->verifyOTP($mobileNumber, $otpCode, 'login');

        if (!$verification['success']) {
            return $verification;
        }

        $mobileNumber = $this->normalizePhoneNumber($mobileNumber);

        // Find user by phone
        $user = User::where('username', $mobileNumber)
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return [
                'success' => false,
                'error' => 'user_not_found',
                'message' => __('messages.auth.user_not_found')
            ];
        }

        // Mark phone as verified if not already
        if (!$user->hasVerifiedPhone()) {
            $user->markPhoneAsVerified();
        }

        // Update last login
        $user->updateLastLogin(request()->ip());

        return [
            'success' => true,
            'user' => $user,
            'message' => __('messages.auth.login_success')
        ];
    }

    /**
     * Normalize phone number to E.164 format
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Handle Kuwait 8-digit numbers
        if (strlen($phone) === 8) {
            $phone = '965' . $phone;
        }

        // Remove leading zeros
        $phone = ltrim($phone, '0');

        // Add + prefix
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
