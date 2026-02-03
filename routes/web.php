<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, config('app.supported_locales', ['en', 'ar']))) {
        session(['locale' => $locale]);
    }
    return back();
})->name('language.switch');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/send-otp', [LoginController::class, 'sendOTP'])->name('login.send-otp');
    Route::get('/verify-otp', [LoginController::class, 'showVerifyOTPForm'])->name('verify-otp.show');
    Route::post('/verify-otp', [LoginController::class, 'verifyOTP'])->name('verify-otp');
    Route::post('/resend-otp', [LoginController::class, 'resendOTP'])->name('resend-otp');
});

// Payment Success/Error (public)
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');

// Authenticated Routes
Route::middleware(['auth', 'agency.active'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::post('/{payment}/resend', [PaymentController::class, 'resendLink'])->name('resend');
        Route::post('/{payment}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    });

    // Team Management
    Route::prefix('team')->name('team.')->middleware('role:admin,super_admin')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::get('/create', [TeamController::class, 'create'])->name('create');
        Route::post('/', [TeamController::class, 'store'])->name('store');
        Route::get('/{member}/edit', [TeamController::class, 'edit'])->name('edit');
        Route::put('/{member}', [TeamController::class, 'update'])->name('update');
        Route::post('/{member}/toggle-status', [TeamController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{member}', [TeamController::class, 'destroy'])->name('destroy');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile');
        Route::put('/password', [SettingsController::class, 'changePassword'])->name('password');

        // MyFatoorah (admin only)
        Route::middleware('role:admin,super_admin')->group(function () {
            Route::get('/myfatoorah', [SettingsController::class, 'myfatoorah'])->name('myfatoorah');
            Route::put('/myfatoorah', [SettingsController::class, 'updateMyfatoorah'])->name('myfatoorah.update');
            Route::post('/myfatoorah/test', [SettingsController::class, 'testMyfatoorah'])->name('myfatoorah.test');
        });

        // Webhooks (admin only)
        Route::middleware('role:admin,super_admin')->group(function () {
            Route::get('/webhooks', [SettingsController::class, 'webhooks'])->name('webhooks');
            Route::post('/webhooks', [SettingsController::class, 'storeWebhook'])->name('webhooks.store');
            Route::delete('/webhooks/{webhook}', [SettingsController::class, 'destroyWebhook'])->name('webhooks.destroy');
            Route::post('/webhooks/{webhook}/toggle', [SettingsController::class, 'toggleWebhook'])->name('webhooks.toggle');
        });
    });

    // Agencies (super admin only)
    Route::prefix('agencies')->name('agencies.')->middleware('role:super_admin')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('index');
        Route::get('/create', [AgencyController::class, 'create'])->name('create');
        Route::post('/', [AgencyController::class, 'store'])->name('store');
        Route::get('/{agency}', [AgencyController::class, 'show'])->name('show');
        Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('edit');
        Route::put('/{agency}', [AgencyController::class, 'update'])->name('update');
        Route::post('/{agency}/toggle-status', [AgencyController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('destroy');
    });

    // Agency details (for agency admin)
    Route::get('/my-agency', [AgencyController::class, 'show'])->name('my-agency')
        ->middleware('role:admin')
        ->defaults('agency', null);
});
