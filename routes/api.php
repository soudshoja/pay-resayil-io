<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\N8nWebhookController;
use App\Http\Controllers\Api\MyFatoorahWebhookController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'service' => 'collect.resayil.io',
    ]);
});

// OTP Authentication
Route::prefix('auth')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOTP']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
});

// n8n Webhooks
Route::prefix('n8n')->group(function () {
    Route::post('/incoming-message', [N8nWebhookController::class, 'handleIncomingMessage'])
        ->name('n8n.incoming');
    Route::post('/generate-payment', [N8nWebhookController::class, 'generatePaymentLink'])
        ->name('n8n.generate-payment');
    Route::get('/payment/{paymentId}/status', [N8nWebhookController::class, 'getPaymentStatus'])
        ->name('n8n.payment-status');
});

// MyFatoorah Webhooks
Route::prefix('myfatoorah')->group(function () {
    Route::get('/callback', [MyFatoorahWebhookController::class, 'handleCallback'])
        ->name('myfatoorah.callback');
    Route::get('/error', [MyFatoorahWebhookController::class, 'handleError'])
        ->name('myfatoorah.error');
    Route::post('/webhook', [MyFatoorahWebhookController::class, 'handleWebhook'])
        ->name('myfatoorah.webhook');
});
