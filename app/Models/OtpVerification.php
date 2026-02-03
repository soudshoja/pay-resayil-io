<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_number',
        'otp_code',
        'purpose',
        'verified_at',
        'expires_at',
        'attempts',
        'ip_address',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * Generate new OTP
     */
    public static function generateFor(
        string $mobileNumber,
        string $purpose = 'login',
        ?string $ipAddress = null
    ): self {
        // Invalidate previous OTPs
        static::where('mobile_number', $mobileNumber)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();

        return static::create([
            'mobile_number' => $mobileNumber,
            'otp_code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(config('services.otp.expiry_minutes', 10)),
            'ip_address' => $ipAddress,
        ]);
    }

    /**
     * Verify OTP code
     */
    public static function verify(
        string $mobileNumber,
        string $otpCode,
        string $purpose = 'login'
    ): ?self {
        $otp = static::where('mobile_number', $mobileNumber)
            ->where('otp_code', $otpCode)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->where('attempts', '<', config('services.otp.max_attempts', 5))
            ->first();

        if ($otp) {
            $otp->update(['verified_at' => now()]);
            return $otp;
        }

        // Increment attempts on failed verification
        static::where('mobile_number', $mobileNumber)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->increment('attempts');

        return null;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP is verified
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Check if max attempts exceeded
     */
    public function hasExceededAttempts(): bool
    {
        return $this->attempts >= config('services.otp.max_attempts', 5);
    }

    /**
     * Check if can resend OTP (cooldown period)
     */
    public static function canResend(string $mobileNumber): bool
    {
        $lastOtp = static::where('mobile_number', $mobileNumber)
            ->latest()
            ->first();

        if (!$lastOtp) {
            return true;
        }

        $cooldown = config('services.otp.resend_cooldown_seconds', 60);
        return $lastOtp->created_at->addSeconds($cooldown)->isPast();
    }

    /**
     * Get seconds until can resend
     */
    public static function secondsUntilCanResend(string $mobileNumber): int
    {
        $lastOtp = static::where('mobile_number', $mobileNumber)
            ->latest()
            ->first();

        if (!$lastOtp) {
            return 0;
        }

        $cooldown = config('services.otp.resend_cooldown_seconds', 60);
        $canResendAt = $lastOtp->created_at->addSeconds($cooldown);

        if ($canResendAt->isPast()) {
            return 0;
        }

        return now()->diffInSeconds($canResendAt);
    }
}
