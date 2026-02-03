<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\N8nWebhookController;
use App\Http\Controllers\Api\N8nApiController;
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

// n8n API Endpoints (New Multi-Tier)
Route::prefix('n8n')->middleware('throttle:100,1')->group(function () {
    // Phone authorization check
    Route::post('/check-phone', [N8nApiController::class, 'checkPhone'])
        ->name('api.n8n.check-phone');

    // Confirm agent details
    Route::post('/confirm-details', [N8nApiController::class, 'confirmDetails'])
        ->name('api.n8n.confirm-details');

    // Create payment
    Route::post('/create-payment', [N8nApiController::class, 'createPayment'])
        ->name('api.n8n.create-payment');

    // Payment completed webhook
    Route::post('/payment-completed', [N8nApiController::class, 'paymentCompleted'])
        ->name('api.n8n.payment-completed');

    // Get payment status
    Route::get('/payment/{invoiceId}/status', [N8nApiController::class, 'getPaymentStatus'])
        ->name('api.n8n.payment-status');

    // Legacy endpoints (backward compatibility)
    Route::post('/incoming-message', [N8nWebhookController::class, 'handleIncomingMessage'])
        ->name('n8n.incoming');
    Route::post('/generate-payment', [N8nWebhookController::class, 'generatePaymentLink'])
        ->name('n8n.generate-payment');
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
