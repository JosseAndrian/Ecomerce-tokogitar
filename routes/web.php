<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/ai-chat', [ChatController::class, 'chat'])->name('ai.chat');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Customer)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/firebase/token', [\App\Http\Controllers\FirebaseController::class, 'getToken'])->name('firebase.token');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES (Protected)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'isCustomer'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    });

    // Payment
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{order}', [PaymentController::class, 'show'])->name('show');
        Route::post('/{order}', [PaymentController::class, 'upload'])->name('upload');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [CustomerOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [CustomerOrderController::class, 'show'])->name('show');
        Route::post('/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('cancel');
    });

    // Reviews
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Chat
    Route::get('/chat', [ChatController::class, 'userIndex'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'userSend'])->name('chat.send');
});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showAdminLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.post');
    });

    /*
    |----------------------------------------------------------------------
    | ADMIN PROTECTED ROUTES
    |----------------------------------------------------------------------
    |
    */
    Route::middleware(['auth', 'isAdmin'])->group(function () {
        // Chat
        Route::get('/chat', [ChatController::class, 'adminIndex'])->name('chat.index');
        Route::get('/chat/{user}', [ChatController::class, 'adminShow'])->name('chat.show');
        Route::post('/chat/{user}', [ChatController::class, 'adminSend'])->name('chat.send');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Products
        Route::resource('products', ProductController::class);

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('status');
            Route::patch('/{order}/verify-payment', [OrderController::class, 'verifyPayment'])->name('verify-payment');
        });

        // Customers
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::delete('/{user}', [CustomerController::class, 'destroy'])->name('destroy');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/print', [ReportController::class, 'print'])->name('print');
            Route::get('/export-csv', [ReportController::class, 'exportCsv'])->name('export-csv');
        });

        // Admin Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
