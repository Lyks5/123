<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;

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