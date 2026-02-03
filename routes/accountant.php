<?php

use App\Http\Controllers\AccountantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Accountant Routes
|--------------------------------------------------------------------------
| Routes for Accountants (view all client transactions, add notes)
*/

Route::prefix('accountant')->middleware(['auth', 'accountant'])->name('accountant.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AccountantController::class, 'dashboard'])->name('dashboard');

    // Transactions
    Route::get('/transactions', [AccountantController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [AccountantController::class, 'exportTransactions'])->name('transactions.export');
    Route::get('/transactions/{payment}', [AccountantController::class, 'showTransaction'])->name('transactions.show');
    Route::post('/transactions/{payment}/notes', [AccountantController::class, 'addNote'])->name('transactions.notes.store');
});
