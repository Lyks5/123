<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BlogManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\SustainabilityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EcoFeaturesController;
use App\Http\Controllers\Admin\ContactRequestController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\InitiativesController;
use App\Http\Controllers\Admin\AttributeController;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Магазин
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/tag/{tag}', [ShopController::class, 'tag'])->name('shop.tag');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Товары
Route::get('/product/review/{product:slug}', [ProductController::class, 'show'])->name('product.review');

Route::middleware('auth')->group(function () {
    Route::post('/product/review/{product:slug}', [ProductController::class, 'submitReview'])->name('product.review.submit');
    Route::get('/product/review/{product:slug}/edit', [ProductController::class, 'editReview'])->name('product.review.edit');
    Route::put('/product/review/{product:slug}', [ProductController::class, 'updateReview'])->name('product.review.update');
});

// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::PATCH('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::PATCH('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');

// Информационные страницы
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/sustainability', [SustainabilityController::class, 'index'])->name('sustainability');

// Блог
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{tag:slug}', [BlogController::class, 'tag'])->name('blog.tag');

// Аутентификация
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Выход
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Личный кабинет
Route::middleware(['auth'])->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::get('/account/addresses', [AccountController::class, 'addresses'])->name('account.addresses');
    Route::post('/account/addresses', [AccountController::class, 'storeAddress'])->name('account.addresses.store');
    Route::put('/account/addresses/{address}', [AccountController::class, 'updateAddress'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [AccountController::class, 'deleteAddress'])->name('account.addresses.delete');
    Route::get('/account/wishlists', [AccountController::class, 'wishlists'])->name('account.wishlists');
});

// Оформление заказа
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon');
Route::delete('/checkout/coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
Route::post('/checkout/shipping', [CheckoutController::class, 'updateShipping'])->name('checkout.shipping');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Административная панель
Route::group([], function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Дашборд
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        
        // Товары
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('index');
            Route::get('/create', [AdminProductController::class, 'create'])->name('create');
            Route::post('/', [AdminProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('delete');
        });
        
        // Категории товаров
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryManagementController::class, 'index'])->name('index');
            Route::get('/create', [CategoryManagementController::class, 'create'])->name('create');
            Route::post('/', [CategoryManagementController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryManagementController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryManagementController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryManagementController::class, 'destroy'])->name('delete');
        });

        // Эко-характеристики
        Route::prefix('eco-features')->name('eco-features.')->group(function () {
            Route::get('/', [EcoFeaturesController::class, 'index'])->name('index');
            Route::get('/create', [EcoFeaturesController::class, 'create'])->name('create');
            Route::post('/', [EcoFeaturesController::class, 'store'])->name('store');
            Route::get('/{ecoFeature}/edit', [EcoFeaturesController::class, 'edit'])->name('edit');
            Route::put('/{ecoFeature}', [EcoFeaturesController::class, 'update'])->name('update');
            Route::delete('/{ecoFeature}', [EcoFeaturesController::class, 'destroy'])->name('delete');
        });

        // Блог - Записи
        Route::prefix('blog/posts')->name('blog.posts.')->group(function () {
            Route::get('/', [BlogManagementController::class, 'index'])->name('index');
            Route::get('/create', [BlogManagementController::class, 'create'])->name('create');
            Route::post('/', [BlogManagementController::class, 'store'])->name('store');
            Route::get('/{post}/edit', [BlogManagementController::class, 'edit'])->name('edit');
            Route::put('/{post}', [BlogManagementController::class, 'update'])->name('update');
            Route::delete('/{post}', [BlogManagementController::class, 'destroy'])->name('delete');
        });
        
        // Блог - Категории
        Route::prefix('blog/categories')->name('blog.categories.')->group(function () {
            Route::get('/', [BlogManagementController::class, 'blogCategories'])->name('index');
            Route::get('/create', [BlogManagementController::class, 'createBlogCategory'])->name('create');
            Route::post('/', [BlogManagementController::class, 'storeBlogCategory'])->name('store');
            Route::get('/{category}/edit', [BlogManagementController::class, 'editBlogCategory'])->name('edit');
            Route::put('/{category}', [BlogManagementController::class, 'updateBlogCategory'])->name('update');
            Route::delete('/{category}', [BlogManagementController::class, 'deleteBlogCategory'])->name('delete');
        });

        // Атрибуты товаров
        Route::prefix('attributes')->name('attributes.')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('index');
            Route::get('/create', [AttributeController::class, 'create'])->name('create');
            Route::post('/', [AttributeController::class, 'store'])->name('store');
            Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('edit');
            Route::put('/{attribute}', [AttributeController::class, 'update'])->name('update');
            Route::delete('/{attribute}', [AttributeController::class, 'destroy'])->name('destroy');
            
            // Значения атрибутов
            Route::prefix('{attribute}/values')->name('values.')->group(function () {
                Route::get('/', [AttributeController::class, 'values'])->name('index');
                Route::get('/create', [AttributeController::class, 'createValue'])->name('create');
                Route::get('/edit/{valueId}', [AttributeController::class, 'editValue'])->name('edit');
                Route::post('/', [AttributeController::class, 'storeValue'])->name('store');
                Route::put('/{valueId}', [AttributeController::class, 'updateValue'])->name('update');
                Route::delete('/{valueId}', [AttributeController::class, 'deleteValue'])->name('delete');
            });
        });
        
        // Пользователи
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'users'])->name('index');
            Route::get('/{user}/edit', [UserManagementController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'updateUser'])->name('update');
        });
        
        // Заказы
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderManagementController::class, 'orders'])->name('index');
            Route::get('/{order}', [OrderManagementController::class, 'showOrder'])->name('show');
            Route::put('/{order}/status', [OrderManagementController::class, 'updateOrderStatus'])->name('update.status');
            Route::get('/{id}/invoice', [OrderManagementController::class, 'printInvoice'])->name('print.invoice');
            Route::get('/{id}/packing-slip', [OrderManagementController::class, 'printPackingSlip'])->name('print.packing-slip');
        });
        
        // Экологические инициативы
        Route::prefix('initiatives')->name('initiatives.')->group(function () {
            Route::get('/', [InitiativesController::class, 'index'])->name('index');
            Route::get('/create', [InitiativesController::class, 'create'])->name('create');
            Route::post('/', [InitiativesController::class, 'store'])->name('store');
            Route::get('/{initiative}/edit', [InitiativesController::class, 'edit'])->name('edit');
            Route::put('/{initiative}', [InitiativesController::class, 'update'])->name('update');
            Route::delete('/{initiative}', [InitiativesController::class, 'destroy'])->name('delete');
        });

        // Обращения пользователей
        Route::prefix('contact-requests')->name('contact-requests.')->group(function () {
            Route::get('/', [ContactRequestController::class, 'index'])->name('index');
            Route::get('/{contactRequest}', [ContactRequestController::class, 'show'])->name('show');
            Route::put('/{contactRequest}/status', [ContactRequestController::class, 'updateStatus'])->name('update.status');
            Route::post('/{contactRequest}/notes', [ContactRequestController::class, 'addNote'])->name('add.note');
            Route::delete('/{contactRequest}', [ContactRequestController::class, 'destroy'])->name('destroy');
        });

        // Аналитика
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('/data', [AnalyticsController::class, 'analytics'])->name('data');
            Route::get('/export/csv', [AnalyticsController::class, 'exportCsv'])->name('export.csv');
            Route::get('/export/pdf', [AnalyticsController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/export/json', [AnalyticsController::class, 'exportJson'])->name('export.json');
        });
    });
});
