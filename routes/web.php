<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/jurnal', [JournalController::class, 'index'])->name('journal.index');
Route::get('/jurnal/{slug}', [JournalController::class, 'show'])->name('journal.show');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('articles', AdminArticleController::class)->except(['show']);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::delete('/media', [AdminMediaController::class, 'destroy'])->name('media.destroy');

        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});
