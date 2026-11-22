<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShippingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// BENAR: Jangan pakai /api/ di depannya
Route::post('/midtrans/callback', [CheckoutController::class, 'callback']);


// Rute API AJAX RajaOngkir
Route::get('/api/cities/{provinceId}', [ShippingController::class, 'getCities']);
Route::post('/api/check-shipping-cost', [ShippingController::class, 'calculateCost']);
