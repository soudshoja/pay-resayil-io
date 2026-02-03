<?php

use App\Http\Controllers\AgentPortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Agent Portal Routes
|--------------------------------------------------------------------------
| Routes for Travel Agency self-service portal
*/

Route::prefix('agent')->name('agent.')->group(function () {

    // Registration Flow
    Route::get('/register', [AgentPortalController::class, 'showRegisterStep1'])->name('register.step1');
    Route::post('/register/send-otp', [AgentPortalController::class, 'sendOTP'])->name('register.send-otp');
    Route::get('/register/verify', [AgentPortalController::class, 'showRegisterStep2'])->name('register.step2');
    Route::post('/register/verify', [AgentPortalController::class, 'verifyOTP'])->name('register.verify-otp');
    Route::get('/register/details', [AgentPortalController::class, 'showRegisterStep3'])->name('register.step3');
    Route::post('/register/complete', [AgentPortalController::class, 'completeRegistration'])->name('register.complete');
    Route::get('/register/success', [AgentPortalController::class, 'registrationSuccess'])->name('register.success');

    // Login Flow (via authorized phone)
    Route::get('/login', [AgentPortalController::class, 'showLogin'])->name('login');
    Route::post('/login/send-otp', [AgentPortalController::class, 'sendPhoneOTP'])->name('login.send-otp');
    Route::get('/login/verify', [AgentPortalController::class, 'showLoginVerify'])->name('login.verify');
    Route::post('/login/verify', [AgentPortalController::class, 'verifyLoginOTP'])->name('login.verify-otp');
    Route::post('/logout', [AgentPortalController::class, 'logout'])->name('logout');

    // Authenticated Agent Routes
    Route::middleware('agent.authenticated')->group(function () {
        Route::get('/dashboard', [AgentPortalController::class, 'dashboard'])->name('dashboard');

        // Payments
        Route::get('/payments', [AgentPortalController::class, 'payments'])->name('payments');

        // Authorized Phones
        Route::get('/phones', [AgentPortalController::class, 'phones'])->name('phones');
        Route::post('/phones', [AgentPortalController::class, 'addPhone'])->name('phones.store');
        Route::delete('/phones/{phone}', [AgentPortalController::class, 'removePhone'])->name('phones.destroy');

        // Settings
        Route::get('/settings', [AgentPortalController::class, 'settings'])->name('settings');
        Route::put('/settings', [AgentPortalController::class, 'updateSettings'])->name('settings.update');
    });
});
