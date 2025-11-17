<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [OwnerController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('transaksi/add-to-cart', [TransaksiController::class, 'addToCart'])->name('transaksi.addToCart');
    Route::delete('transaksi/remove-from-cart/{index}', [TransaksiController::class, 'removeFromCart'])->name('transaksi.removeFromCart');
    Route::post('transaksi/update-cart-quantity/{index}', [TransaksiController::class, 'updateCartQuantity'])->name('transaksi.updateCartQuantity');
    Route::post('transaksi/confirm-order', [TransaksiController::class, 'confirmOrder'])->name('transaksi.confirmOrder');
    Route::get('transaksi/confirm', [TransaksiController::class, 'confirm'])->name('transaksi.confirm');
    Route::get('transaksi/export-pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.exportPdf');

    Route::get('barang-return', [TransaksiController::class, 'listReturnableTransaksi'])->name('transaksi.listReturnable');
    Route::get('barang-return/{id}', [TransaksiController::class, 'barangReturn'])->name('transaksi.barangReturn');
    Route::post('barang-return/{id}', [TransaksiController::class, 'return'])->name('transaksi.return');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::match(['GET', 'POST'], 'data-customer', [OwnerController::class, 'dataCustomer'])->name('owner.dataCustomer');
    Route::resource('user', UserController::class);

    Route::get('laporan-penjualan', [OwnerController::class, 'laporanPenjualan'])->name('owner.laporanPenjualan');
    Route::get('laporan-penjualan/export', [OwnerController::class, 'laporanPenjualanExport'])->name('owner.laporanPenjualanExport');
    Route::get('laporan-barang-return', [OwnerController::class, 'laporanBarangReturn'])->name('owner.laporanBarangReturn');
    Route::get('dashboard/chart-data', [OwnerController::class, 'chartData'])->name('dashboard.chartData');
});

// Routes for user activation (public routes)
Route::get('user/activate/{token}', [UserController::class, 'activate'])->name('user.activate');
Route::post('user/activate', [UserController::class, 'activateStore'])->name('user.activate.store');

require __DIR__.'/auth.php';
