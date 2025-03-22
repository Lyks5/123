<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SustainabilityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Магазин
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

// Товары
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::PATCH('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::PATCH('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Информационные страницы
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/sustainability', [SustainabilityController::class, 'index'])->name('sustainability');

Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search'); // Added search route
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{tag:slug}', [BlogController::class, 'tag'])->name('blog.tag');

// Аутентификация
Route::middleware(['guest'])->group(function () {
    // Вход
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Регистрация
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Сброс пароля
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Выход
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Верификация Email
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});
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
    Route::get('/account/wishlists', [AccountController::class, 'wishlists'])->name('account.wishlists'); // Added wishlist route
});

// Оформление заказа
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon');
Route::delete('/checkout/coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
Route::post('/checkout/shipping', [CheckoutController::class, 'updateShipping'])->name('checkout.shipping');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Аналитика
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::prefix('admin')->name('admin.')->group(function () {
    // Дашборд
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Товары
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminController::class, 'products'])->name('index');
        Route::get('/create', [AdminController::class, 'createProduct'])->name('create');
        Route::post('/', [AdminController::class, 'storeProduct'])->name('store');
        Route::get('/{product}/edit', [AdminController::class, 'editProduct'])->name('edit');
        Route::put('/{product}', [AdminController::class, 'updateProduct'])->name('update');
        Route::delete('/{product}', [AdminController::class, 'deleteProduct'])->name('delete');
    });
    
    // Категории товаров
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminController::class, 'categories'])->name('index');
        Route::get('/create', [AdminController::class, 'createCategory'])->name('create');
        Route::post('/', [AdminController::class, 'storeCategory'])->name('store');
        Route::get('/{category}/edit', [AdminController::class, 'editCategory'])->name('edit');
        Route::put('/{category}', [AdminController::class, 'updateCategory'])->name('update');
        Route::delete('/{category}', [AdminController::class, 'deleteCategory'])->name('delete');
    });
    // Эко-характеристики
    Route::prefix('eco-features')->name('eco-features.')->group(function () {
        Route::get('/', [AdminController::class, 'ecoFeatures'])->name('index');
        Route::get('/create', [AdminController::class, 'createEcoFeature'])->name('create');
        Route::post('/', [AdminController::class, 'storeEcoFeature'])->name('store');
        Route::get('/{ecoFeature}/edit', [AdminController::class, 'editEcoFeature'])->name('edit');
        Route::put('/{ecoFeature}', [AdminController::class, 'updateEcoFeature'])->name('update');
        Route::delete('/{ecoFeature}', [AdminController::class, 'deleteEcoFeature'])->name('delete');
    });
    // Блог - Записи
    Route::prefix('blog/posts')->name('blog.posts.')->group(function () {
        Route::get('/', [AdminController::class, 'blogPosts'])->name('index');
        Route::get('/create', [AdminController::class, 'createBlogPost'])->name('create');
        Route::post('/', [AdminController::class, 'storeBlogPost'])->name('store');
        Route::get('/{post}/edit', [AdminController::class, 'editBlogPost'])->name('edit');
        Route::put('/{post}', [AdminController::class, 'updateBlogPost'])->name('update');
        Route::delete('/{post}', [AdminController::class, 'deleteBlogPost'])->name('delete');
    });
    
    // Блог - Категории
    Route::prefix('blog/categories')->name('blog.categories.')->group(function () {
        Route::get('/', [AdminController::class, 'blogCategories'])->name('index');
        Route::get('/create', [AdminController::class, 'createBlogCategory'])->name('create');
        Route::post('/', [AdminController::class, 'storeBlogCategory'])->name('store');
        Route::get('/{category}/edit', [AdminController::class, 'editBlogCategory'])->name('edit');
        Route::put('/{category}', [AdminController::class, 'updateBlogCategory'])->name('update');
        Route::delete('/{category}', [AdminController::class, 'deleteBlogCategory'])->name('delete');
    });
    // Атрибуты товаров
    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', [AdminController::class, 'attributes'])->name('index');
        Route::get('/create', [AdminController::class, 'createAttribute'])->name('create');
        Route::post('/', [AdminController::class, 'storeAttribute'])->name('store');
        Route::get('/{attribute}/edit', [AdminController::class, 'editAttribute'])->name('edit');
        Route::put('/{attribute}', [AdminController::class, 'updateAttribute'])->name('update');
        Route::delete('/{attribute}', [AdminController::class, 'deleteAttribute'])->name('destroy');
        
        // Значения атрибутов
        Route::get('/{attribute}/values', [AdminController::class, 'attributeValues'])->name('values.index');
        Route::post('/{attribute}/values', [AdminController::class, 'storeAttributeValue'])->name('values.store');
        Route::put('/{attribute}/values/{value}', [AdminController::class, 'updateAttributeValue'])->name('values.update');
        Route::delete('/{attribute}/values/{value}', [AdminController::class, 'deleteAttributeValue'])->name('values.delete');
    });
    
    // Пользователи
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/{user}/edit', [AdminController::class, 'editUser'])->name('edit');
        Route::put('/{user}', [AdminController::class, 'updateUser'])->name('update');
    });
    
    // Заказы
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminController::class, 'orders'])->name('index');
        Route::get('/{order}', [AdminController::class, 'showOrder'])->name('show');
        Route::put('/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('update.status');
    });
    
    // Экологические инициативы
    Route::prefix('initiatives')->name('initiatives.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'initiatives'])->name('index');
        Route::get('/create', [AdminController::class, 'createInitiative'])->name('create');
        Route::post('/', [AdminController::class, 'storeInitiative'])->name('store');
        Route::get('/{initiative}/edit', [AdminController::class, 'editInitiative'])->name('edit');
        Route::put('/{initiative}', [AdminController::class, 'updateInitiative'])->name('update');
        Route::delete('/{initiative}', [AdminController::class, 'deleteInitiative'])->name('delete');
    });
    // Обращения пользователей
    Route::prefix('contact-requests')->name('contact-requests.')->group(function () {
        Route::get('/', [AdminController::class, 'contactRequests'])->name('index');
        Route::get('/{contactRequest}', [AdminController::class, 'showContactRequest'])->name('show');
        Route::put('/{contactRequest}/status', [AdminController::class, 'updateContactRequestStatus'])->name('update.status');
        Route::post('/{contactRequest}/notes', [AdminController::class, 'addContactRequestNote'])->name('add.note');
        Route::delete('/{contactRequest}', [AdminController::class, 'deleteContactRequest'])->name('destroy'); // Added delete route
    });
     // Аналитика
     Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});
