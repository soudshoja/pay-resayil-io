<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sales Person Routes
|--------------------------------------------------------------------------
| Routes for Sales Persons (only see their assigned agents)
*/

Route::prefix('sales')->middleware(['auth', 'sales.person'])->name('sales.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [SalesController::class, 'dashboard'])->name('dashboard');

    // My Agents
    Route::get('/agents', [SalesController::class, 'agents'])->name('agents');
    Route::get('/agents/{agent}', [SalesController::class, 'showAgent'])->name('agents.show');

    // My Transactions
    Route::get('/transactions', [SalesController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [SalesController::class, 'exportTransactions'])->name('transactions.export');
});
