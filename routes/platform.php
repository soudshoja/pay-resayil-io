<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlatformController;

/*
|--------------------------------------------------------------------------
| Platform Owner Routes
|--------------------------------------------------------------------------
|
| These routes are for the platform owner (Soud) to access the system
| with special privileges across all agencies.
|
*/

// Platform Login (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/platform/login', [PlatformController::class, 'showLogin'])
        ->name('platform.login');
    Route::post('/platform/login', [PlatformController::class, 'login'])
        ->name('platform.login.submit');
});

// Platform Dashboard (authenticated platform owner only)
Route::middleware(['auth', 'platform.owner'])->prefix('platform')->group(function () {
    Route::get('/dashboard', [PlatformController::class, 'dashboard'])
        ->name('platform.dashboard');
    Route::post('/logout', [PlatformController::class, 'logout'])
        ->name('platform.logout');
});
