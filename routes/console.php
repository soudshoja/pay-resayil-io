<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Expire pending payments after 24 hours
Schedule::call(function () {
    \App\Models\PaymentRequest::where('status', 'pending')
        ->where('created_at', '<', now()->subHours(24))
        ->update(['status' => 'expired']);
})->hourly()->name('expire-payments');

// Clean old OTP records
Schedule::call(function () {
    \App\Models\OtpVerification::where('expires_at', '<', now()->subDays(1))
        ->delete();
})->daily()->name('cleanup-otp');
