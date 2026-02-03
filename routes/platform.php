<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatformController;

/*
|--------------------------------------------------------------------------
| Platform Owner Routes
|--------------------------------------------------------------------------
*/

// Platform Login (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/platform/login', [PlatformController::class, 'showLogin'])
        ->name('platform.login');
    Route::post('/platform/login', [PlatformController::class, 'login'])
        ->name('platform.login.submit');
});

// Platform Dashboard & Management (authenticated platform owner only)
Route::middleware(['auth', 'platform.owner'])->prefix('platform')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PlatformController::class, 'dashboard'])
        ->name('platform.dashboard');

    // Agencies Management
    Route::get('/agencies', [PlatformController::class, 'agencies'])
        ->name('platform.agencies');
    Route::get('/agencies/{agency}', [PlatformController::class, 'showAgency'])
        ->name('platform.agencies.show');
    Route::get('/agencies/{agency}/edit', [PlatformController::class, 'editAgency'])
        ->name('platform.agencies.edit');
    Route::put('/agencies/{agency}', [PlatformController::class, 'updateAgency'])
        ->name('platform.agencies.update');
    Route::post('/agencies/{agency}/toggle-status', [PlatformController::class, 'toggleAgencyStatus'])
        ->name('platform.agencies.toggle-status');
    Route::delete('/agencies/{agency}', [PlatformController::class, 'deleteAgency'])
        ->name('platform.agencies.delete');

    // Users Management
    Route::get('/users', [PlatformController::class, 'users'])
        ->name('platform.users');
    Route::get('/users/{user}/edit', [PlatformController::class, 'editUser'])
        ->name('platform.users.edit');
    Route::put('/users/{user}', [PlatformController::class, 'updateUser'])
        ->name('platform.users.update');
    Route::post('/users/{user}/toggle-status', [PlatformController::class, 'toggleUserStatus'])
        ->name('platform.users.toggle-status');
    Route::post('/users/{user}/impersonate', [PlatformController::class, 'impersonateUser'])
        ->name('platform.users.impersonate');
    Route::post('/stop-impersonation', [PlatformController::class, 'stopImpersonation'])
        ->name('platform.stop-impersonation');

    // Payments
    Route::get('/payments', [PlatformController::class, 'payments'])
        ->name('platform.payments');
    Route::get('/payments/export', [PlatformController::class, 'exportPayments'])
        ->name('platform.payments.export');

    // Settings
    Route::get('/settings', [PlatformController::class, 'settings'])
        ->name('platform.settings');
    Route::post('/settings', [PlatformController::class, 'updateSettings'])
        ->name('platform.settings.update');

    // Activity Logs
    Route::get('/logs', [PlatformController::class, 'logs'])
        ->name('platform.logs');

    // Logout
    Route::post('/logout', [PlatformController::class, 'logout'])
        ->name('platform.logout');
});
