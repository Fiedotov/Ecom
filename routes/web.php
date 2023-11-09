<?php

use App\Http\Controllers\Checkout;
use App\Http\Controllers\Confirmation;
use App\Http\Controllers\SearchLandForSale;
use App\Http\Controllers\ShowProperty;
use App\Http\Controllers\ShowPropertyImage;
use Illuminate\Support\Facades\Route;

Route::get('/new-property-search', SearchLandForSale::class)->name('home');

Route::group(['prefix' => 'new-property'], function () {
    Route::get('/{id}/image', ShowPropertyImage::class)->name('properties.image.show');
    Route::get('/{apn}', ShowProperty::class)->name('properties.show');
    Route::get('/{apn}/checkout', Checkout::class)->name('properties.checkout');
});

Route::get('/confirmation/{transactionId}', Confirmation::class)->name('confirmation');

include 'dashboard.php';

require __DIR__ . '/auth.php';
