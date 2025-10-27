<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;
use Illuminate\Support\Facades\Auth;

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

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $penjualanBulanIni = Transaksi::whereYear('tanggal_pembelian', $currentYear)
            ->whereMonth('tanggal_pembelian', $currentMonth)
            ->where('status', 'selesai')
            ->sum('total_harga');

        // Data untuk chart penjualan perbulan (series 1)
        $penjualanPerBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = Transaksi::whereYear('tanggal_pembelian', $date->year)
                ->whereMonth('tanggal_pembelian', $date->month)
                ->where('status', 'selesai')
                ->sum('total_harga');
            $penjualanPerBulan[] = $total;
        }

        // Data untuk chart barang return perbulan (series 2)
        $returnPerBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = Transaksi::whereYear('tanggal_pembelian', $date->year)
                ->whereMonth('tanggal_pembelian', $date->month)
                ->whereIn('status', ['return', 'return_partial'])
                ->sum('total_harga'); // Jumlah harga barang yang direturn
            $returnPerBulan[] = $total;
        }

        // Kategori untuk xaxis (nama bulan)
        $categories = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $categories[] = $date->format('M Y'); // Format: Jan 2024, Feb 2024, etc.
        }

        return view('dashboard', compact(
            'totalProduk',
            'totalCustomer',
            'totalTransaksi',
            'penjualanHariIni',
            'penjualanBulanIni',
            'penjualanPerBulan',
            'returnPerBulan',
            'categories'
        ));
    }

    public function dataBarang(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barangs = $query->orderBy('created_at', 'desc')->get();

        // Update last viewed timestamp for the current user
        \App\Models\User::where('id', Auth::id())->update(['last_viewed_barang_at' => now()]);

        return view('owner.data-barang', compact('barangs'));
    }

    public function dataCustomer()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();

        // Update last viewed timestamp for the current user
        \App\Models\User::where('id', Auth::id())->update(['last_viewed_customer_at' => now()]);

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

    public function laporanPenjualanExport()
    {
        return Excel::download(new LaporanPenjualanExport, 'laporan_penjualan.xlsx');
    }
}
