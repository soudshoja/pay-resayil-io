<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
| Routes for Client Admin (Fly Dubai admin, etc.)
*/

Route::prefix('client')->middleware(['auth', 'client.admin'])->name('client.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

    // Agents Management
    Route::get('/agents', [ClientController::class, 'agents'])->name('agents');
    Route::get('/agents/create', [ClientController::class, 'createAgent'])->name('agents.create');
    Route::post('/agents', [ClientController::class, 'storeAgent'])->name('agents.store');
    Route::get('/agents/{agent}', [ClientController::class, 'showAgent'])->name('agents.show');
    Route::get('/agents/{agent}/edit', [ClientController::class, 'editAgent'])->name('agents.edit');
    Route::put('/agents/{agent}', [ClientController::class, 'updateAgent'])->name('agents.update');
    Route::delete('/agents/{agent}', [ClientController::class, 'destroyAgent'])->name('agents.destroy');

    // Agent Authorized Phones
    Route::post('/agents/{agent}/phones', [ClientController::class, 'addAuthorizedPhone'])->name('agents.phones.store');
    Route::delete('/phones/{phone}', [ClientController::class, 'removeAuthorizedPhone'])->name('phones.destroy');

    // Sales Persons Management
    Route::get('/sales-persons', [ClientController::class, 'salesPersons'])->name('sales-persons');
    Route::get('/sales-persons/create', [ClientController::class, 'createSalesPerson'])->name('sales-persons.create');
    Route::post('/sales-persons', [ClientController::class, 'storeSalesPerson'])->name('sales-persons.store');
    Route::get('/sales-persons/{user}/edit', [ClientController::class, 'editSalesPerson'])->name('sales-persons.edit');
    Route::put('/sales-persons/{user}', [ClientController::class, 'updateSalesPerson'])->name('sales-persons.update');
    Route::delete('/sales-persons/{user}', [ClientController::class, 'destroySalesPerson'])->name('sales-persons.destroy');

    // Accountants Management
    Route::get('/accountants', [ClientController::class, 'accountants'])->name('accountants');
    Route::get('/accountants/create', [ClientController::class, 'createAccountant'])->name('accountants.create');
    Route::post('/accountants', [ClientController::class, 'storeAccountant'])->name('accountants.store');
    Route::get('/accountants/{user}/edit', [ClientController::class, 'editAccountant'])->name('accountants.edit');
    Route::put('/accountants/{user}', [ClientController::class, 'updateAccountant'])->name('accountants.update');
    Route::delete('/accountants/{user}', [ClientController::class, 'destroyAccountant'])->name('accountants.destroy');

    // Transactions
    Route::get('/transactions', [ClientController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [ClientController::class, 'exportTransactions'])->name('transactions.export');

    // WhatsApp Keywords
    Route::get('/keywords', [ClientController::class, 'keywords'])->name('keywords');
    Route::post('/keywords', [ClientController::class, 'storeKeyword'])->name('keywords.store');
    Route::post('/keywords/{keyword}/toggle', [ClientController::class, 'toggleKeyword'])->name('keywords.toggle');
    Route::delete('/keywords/{keyword}', [ClientController::class, 'destroyKeyword'])->name('keywords.destroy');

    // WhatsApp
    Route::get('/whatsapp', [ClientController::class, 'whatsapp'])->name('whatsapp');

    // Settings
    Route::get('/settings', [ClientController::class, 'settings'])->name('settings');
    Route::put('/settings', [ClientController::class, 'updateSettings'])->name('settings.update');
});
