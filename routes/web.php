<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\ProfilPasarController;
use App\Http\Controllers\Admin\StokMasukController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kasir\PelangganController as KasirPelangganController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\TransaksiController as KasirTransaksiController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\KatalogController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\Public\RatingController;
use App\Http\Controllers\Webhook\MidtransController;
use Illuminate\Support\Facades\Route;

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

// Lacak Pesanan & Ulasan Pembeli
Route::get('/lacak-pesanan', [CheckoutController::class, 'track'])->name('public.track');
Route::post('/rating', [RatingController::class, 'store'])->name('public.rating.store');

// Webhook Midtrans (Tanpa Auth)
Route::post('/midtrans/callback', [MidtransController::class, 'handle']);

// ===== AUTH (Breeze) =====
require __DIR__.'/auth.php';

// ===== DASHBOARD (AUTH & ROLE) =====
Route::middleware(['auth'])->group(function () {

    // Dashboard redirector (Admin & Kasir)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== ADMIN =====
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Profil Pasar
        Route::get('/profil', [ProfilPasarController::class, 'index'])->name('profil.index');
        Route::put('/profil', [ProfilPasarController::class, 'update'])->name('profil.update');

        // Kategori
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Produk
        Route::resource('/produk', ProdukController::class);

        // Supplier
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
        Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
        Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

        // Stok Masuk
        Route::get('/stok', [StokMasukController::class, 'index'])->name('stok.index');
        Route::get('/stok/create', [StokMasukController::class, 'create'])->name('stok.create');
        Route::post('/stok', [StokMasukController::class, 'store'])->name('stok.store');
        Route::get('/stok/{stok}', [StokMasukController::class, 'show'])->name('stok.show');

        // Pesanan Online
        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
        Route::put('/pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('pesanan.status');

        // Semua Transaksi
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::post('/transaksi/{transaksi}/void', [TransaksiController::class, 'void'])->name('transaksi.void');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');

        // Ulasan
        Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
        Route::put('/ulasan/{rating}/status', [UlasanController::class, 'updateStatus'])->name('ulasan.status');
        Route::delete('/ulasan/{rating}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

        // User
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

        // QR Code
        Route::get('/qrcode', [PengaturanController::class, 'qrCode'])->name('qrcode');

        // Pelanggan & Log
        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log.index');
    });

    // ===== KASIR =====
    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {

        // POS
        Route::get('/pos', [PosController::class, 'index'])->name('pos');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/struk/{transaksi}', [PosController::class, 'struk'])->name('pos.struk');

        // Riwayat Transaksi Kasir
        Route::get('/transaksi', [KasirTransaksiController::class, 'index'])->name('transaksi');

        // Pelanggan Kasir
        Route::get('/pelanggan', [KasirPelangganController::class, 'index'])->name('pelanggan');
        Route::post('/pelanggan', [KasirPelangganController::class, 'store'])->name('pelanggan.store');
        Route::put('/pelanggan/{pelanggan}', [KasirPelangganController::class, 'update'])->name('pelanggan.update');
    });
});