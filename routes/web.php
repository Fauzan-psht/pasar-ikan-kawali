<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\KatalogController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\RatingController;
use App\Http\Controllers\DashboardController;

// ===== ROUTE PUBLIK =====
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/profil', [HomeController::class, 'profil'])->name('public.profil');
Route::get('/katalog', [KatalogController::class, 'index'])->name('public.katalog');
Route::get('/katalog/{slug}', [KatalogController::class, 'detail'])->name('public.produk.detail');
Route::get('/kontak', [KontakController::class, 'index'])->name('public.kontak');

// ===== ROUTE KERANJANG & CHECKOUT =====
Route::get('/cart', [CartController::class, 'index'])->name('public.cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('public.cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('public.cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('public.cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('public.checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('public.checkout.process');
Route::get('/checkout/success/{nomor}', [CheckoutController::class, 'success'])->name('public.checkout.success');

// Ulasan Pembeli
Route::post('/rating', [RatingController::class, 'store'])->name('public.rating.store');

// Lacak Pesanan
Route::get('/lacak-pesanan', [CheckoutController::class, 'track'])->name('public.track');

// ===== AUTH (Breeze) =====
require __DIR__.'/auth.php';

// ===== DASHBOARD =====
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== ADMIN =====
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Profil
        Route::get('/profil', [\App\Http\Controllers\Admin\ProfilPasarController::class, 'index'])->name('profil.index');
        Route::put('/profil', [\App\Http\Controllers\Admin\ProfilPasarController::class, 'update'])->name('profil.update');
        
        // Kategori
        Route::get('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [\App\Http\Controllers\Admin\KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{kategori}', [\App\Http\Controllers\Admin\KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [\App\Http\Controllers\Admin\KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Produk
        Route::resource('/produk', \App\Http\Controllers\Admin\ProdukController::class);

        // Pesanan
        Route::get('/pesanan', [\App\Http\Controllers\Admin\PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [\App\Http\Controllers\Admin\PesananController::class, 'show'])->name('pesanan.show');
        Route::put('/pesanan/{pesanan}/status', [\App\Http\Controllers\Admin\PesananController::class, 'updateStatus'])->name('pesanan.status');

        // Transaksi
        Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');

        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');

        // Ulasan
        Route::get('/ulasan', [\App\Http\Controllers\Admin\UlasanController::class, 'index'])->name('ulasan.index');
        Route::put('/ulasan/{rating}/status', [\App\Http\Controllers\Admin\UlasanController::class, 'updateStatus'])->name('ulasan.status');
        Route::delete('/ulasan/{rating}', [\App\Http\Controllers\Admin\UlasanController::class, 'destroy'])->name('ulasan.destroy');

        // User
        Route::get('/user', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('user.index');
        Route::post('/user', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('user.store');
        Route::put('/user/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('user.destroy');

        // QR Code & Placeholder
        Route::get('/qrcode', [\App\Http\Controllers\Admin\PengaturanController::class, 'qrCode'])->name('qrcode');
        Route::view('/supplier', 'admin.placeholder', ['title' => 'Supplier'])->name('supplier.index');
        Route::view('/stok', 'admin.placeholder', ['title' => 'Stok Masuk'])->name('stok.index');
    });

    // ===== KASIR =====
    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/pos', [\App\Http\Controllers\Kasir\PosController::class, 'index'])->name('pos');
        Route::post('/pos', [\App\Http\Controllers\Kasir\PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/struk/{transaksi}', [\App\Http\Controllers\Kasir\PosController::class, 'struk'])->name('pos.struk');
        Route::get('/transaksi', [\App\Http\Controllers\Kasir\TransaksiController::class, 'index'])->name('transaksi');
        Route::view('/pelanggan', 'admin.placeholder', ['title' => 'Pelanggan'])->name('pelanggan');
    });
});