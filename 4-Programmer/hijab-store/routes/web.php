<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ReportController;

// Rute API AJAX RajaOngkir untuk Checkout
Route::get('/api/cities/{provinceId}', [ShippingController::class, 'getCities']);
Route::post('/api/check-shipping-cost', [ShippingController::class, 'calculateCost']);

// 1. Rute Publik (Beranda, Katalog, Best Seller, Perawatan Kain & Detail Produk)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [HomeController::class, 'catalog'])->name('katalog.index');
Route::get('/best-seller', [HomeController::class, 'bestSeller'])->name('bestseller.index');
Route::view('/perawatan-kain', 'fabric-care')->name('fabric-care');
Route::get('/produk/{slug}', [HomeController::class, 'show'])->name('produk.detail');

// 2. Rute Keranjang Belanja
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{variantId}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/keranjang/hapus/{variantId}', [CartController::class, 'remove'])->name('cart.remove');

// 3. Rute Checkout & Pesanan Sukses
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/proses', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/pesanan/sukses/{id}', [CheckoutController::class, 'success'])->name('order.success');

// 4. Rute Lacak Pesanan
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('order.track');
Route::post('/track-order', [OrderTrackingController::class, 'search'])->name('order.track.search');

// 5. Rute Panel Admin (DIBUNGKUS MIDDLEWARE AUTH, PREFIX /admin, & NAME admin.)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Manajemen Pesanan Admin
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}', [AdminOrderController::class, 'update'])->name('orders.update');

    // Kelola Produk Admin
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/tambah', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Kelola Kategori Produk Admin
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Kelola Hero Banner Admin
    Route::resource('banners', BannerController::class)->except(['show']);

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stok per Varian Admin
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::put('/stocks/{id}', [StockController::class, 'update'])->name('stocks.update');

    // Rekap Penjualan (Khusus Pemilik)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// 6. Rute Dashboard Bawaan Breeze (Wajib Login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 7. Rute Profil Pengguna (Bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 8. Rute Autentikasi (Login, Register, Logout, dll)
require __DIR__.'/auth.php';