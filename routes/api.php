<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Products filter API endpoint
Route::get('products/filter', [App\Http\Controllers\ShopController::class, 'filter']);

// Корзина и заказы (требуют аутентификации)
Route::middleware('auth:sanctum')->group(function () {
    // Корзина
    Route::post('cart/add', [CartController::class, 'add']);
    Route::get('cart', [CartController::class, 'index']);
    Route::delete('cart/{product}', [CartController::class, 'remove']);
    
    // Заказы
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
});

// Административные маршруты
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::post('products/upload-image', [ProductController::class, 'uploadImage']);
    Route::delete('products/images/{image}', [ProductController::class, 'deleteImage']);
    Route::post('products/draft', [ProductController::class, 'storeDraft']);
    Route::put('products/{product}/draft', [ProductController::class, 'updateDraft']);
    Route::post('products/{product}/publish', [ProductController::class, 'publish']);
});