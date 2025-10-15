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

        return view('dashboard', compact('totalProduk', 'totalCustomer', 'totalTransaksi', 'penjualanHariIni'));
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
