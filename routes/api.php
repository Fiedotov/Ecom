<?php

use App\Http\Controllers\SendPasswordReset;
use App\Http\Controllers\ShowDocusign;
use App\Http\Controllers\StoreOneTimePayment;
use App\Http\Controllers\StorePropertyInquiry;
use App\Http\Controllers\SubmitAchPayment;
use App\Http\Controllers\SubmitTransaction;
use Illuminate\Support\Facades\Route;

Route::get('/docusign/{submission}', ShowDocusign::class)->name('docusign.show');
Route::post('/property-inquiries/{property}', StorePropertyInquiry::class)->name('property-inquiries.store');
Route::post('/transactions/{apn}', SubmitTransaction::class)->name('transactions.submit');
Route::post('/ach-payments', SubmitAchPayment::class)->name('ach-payments.submit');
Route::post('/payments', StoreOneTimePayment::class)->name('payments.store');
Route::post('/reset-password', SendPasswordReset::class)->name('salesforce.password.email');
