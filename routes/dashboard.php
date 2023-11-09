<?php

use App\Http\Controllers\Dashboard\Account;
use App\Http\Controllers\Dashboard\Properties;
use App\Http\Controllers\Dashboard\ShowProperty;
use App\Http\Controllers\Dashboard\ShowPayments;
use App\Http\Controllers\DeletePaymentMethod;
use App\Http\Controllers\StorePaymentMethod;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['prefix' => '/dashboard', 'middleware' => ['auth', 'verified']], function () {
    Route::get('/', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/account', Account::class)->name('account');
    Route::get('/properties', Properties::class)->name('dashboard.properties');
    Route::get('/properties/{contract}', ShowProperty::class)->name('dashboard.properties.show');
    Route::post('/payment-methods', StorePaymentMethod::class)->name('dashboard.payment-methods.store');
    Route::delete('/payment-methods', DeletePaymentMethod::class)->name('dashboard.payment-methods.delete');
    Route::get('/payments', ShowPayments::class)->name('dashboard.payments');
});