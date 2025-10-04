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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::resource('barang', BarangController::class);
    Route::resource('customer', CustomerController::class);
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('transaksi/add-to-cart', [TransaksiController::class, 'addToCart'])->name('transaksi.addToCart');
    Route::post('transaksi/confirm-order', [TransaksiController::class, 'confirmOrder'])->name('transaksi.confirmOrder');
    Route::get('transaksi/confirm', [TransaksiController::class, 'confirm'])->name('transaksi.confirm');
    Route::get('transaksi/export-pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.exportPdf');
    Route::get('transaksi/batal', function () {
        return redirect()->route('transaksi.listBatal');
    });
    Route::post('transaksi/batal', [TransaksiController::class, 'batal'])->name('transaksi.batal');
    Route::get('transaksi/batal-list/{id?}', [TransaksiController::class, 'listBatal'])->name('transaksi.listBatal');
    Route::get('barang-return', [TransaksiController::class, 'listReturnableTransaksi'])->name('transaksi.listReturnable');
    Route::get('barang-return/{id}', [TransaksiController::class, 'barangReturn'])->name('transaksi.barangReturn');
    Route::post('barang-return/{id}', [TransaksiController::class, 'return'])->name('transaksi.return');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('data-barang', [OwnerController::class, 'dataBarang']);
    Route::get('data-customer', [OwnerController::class, 'dataCustomer']);
    Route::resource('user', UserController::class);
});

require __DIR__.'/auth.php';






