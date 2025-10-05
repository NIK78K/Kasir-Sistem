<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $totalProduk = Barang::count();
        $totalCustomer = Customer::count();
        $totalTransaksi = Transaksi::count();

        $today = Carbon::today();
        $penjualanHariIni = Transaksi::whereDate('tanggal_pembelian', $today)
            ->where('status', 'selesai')
            ->sum('total_harga');

        return view('dashboard', compact('totalProduk', 'totalCustomer', 'totalTransaksi', 'penjualanHariIni'));
    }

    public function dataBarang()
    {
        $barangs = Barang::all();
        return view('owner.data-barang', compact('barangs'));
    }

    public function dataCustomer()
    {
        $customers = Customer::all();
        return view('owner.data-customer', compact('customers'));
    }

    public function laporanPenjualan()
    {
        $transaksis = Transaksi::with('barang', 'customer')
            ->where('status', 'selesai')
            ->latest()
            ->paginate(10);

        return view('transaksi.batal', compact('transaksis'));
    }

    public function laporanBarangReturn()
    {
        $transaksis = Transaksi::with('barang', 'customer')
            ->whereIn('status', ['return', 'return_partial'])
            ->latest()
            ->paginate(10);

        return view('transaksi.list_return', compact('transaksis'));
    }
}
