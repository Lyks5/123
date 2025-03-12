<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdditionalServiceController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
// Остальные маршруты...
Route::resource('users', UserController::class);
Route::resource('promotions', PromotionController::class);
Route::resource('locations', LocationController::class);
Route::resource('zones', ZoneController::class);
Route::resource('bookings', BookingController::class);
Route::resource('additional-services', AdditionalServiceController::class);
Route::resource('reviews', ReviewController::class);