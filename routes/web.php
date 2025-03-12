<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdditionalServiceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
// routes/web.php

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
