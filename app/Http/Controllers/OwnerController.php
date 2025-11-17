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
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        // Hitung hanya produk aktif (tidak di-soft delete)
        $totalProduk = Barang::active()->count();
        $totalCustomer = Customer::count();
        $totalTransaksi = Transaksi::where('status', 'selesai')
            ->selectRaw('COUNT(DISTINCT order_id) as total_orders')
            ->first()
            ->total_orders;

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



    public function dataCustomer(Request $request)
    {
        // Handle AJAX request to update viewed status
        if ($request->isMethod('post') && $request->input('action') === 'update_viewed') {
            if (Auth::check() && Auth::user()->role === 'owner') {
                DB::table('users')->where('id', Auth::id())->update(['last_viewed_customer_at' => now()]);
            }
            return response()->json(['success' => true]);
        }

        $query = Customer::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_customer', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('no_hp', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by tipe_pembeli
        if ($request->filled('tipe_pembeli')) {
            $query->where('tipe_pembeli', $request->tipe_pembeli);
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        // Get all available buyer types
        $allTipePembeli = ['pembeli', 'bengkel_langganan'];

        // Update last viewed timestamp for the current user
        \App\Models\User::where('id', Auth::id())->update(['last_viewed_customer_at' => now()]);

        return view('owner.data-customer', compact('customers', 'allTipePembeli'));
    }

    public function laporanPenjualan(Request $request)
    {
        // Base query
        $query = Transaksi::with('barang', 'customer')
            ->where('status', 'selesai');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($searchTerm) {
                      $customerQuery->where('nama_customer', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('barang', function($barangQuery) use ($searchTerm) {
                      $barangQuery->where('nama_barang', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by payment method
        if ($request->filled('tipe_pembayaran')) {
            $query->where('tipe_pembayaran', $request->tipe_pembayaran);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_sampai);
        }

        // Group transactions by order_id and get aggregated data
        $orders = $query->selectRaw('
                order_id,
                customer_id,
                MIN(tanggal_pembelian) as tanggal_pembelian,
                tipe_pembayaran,
                SUM(jumlah) as total_jumlah,
                SUM(total_harga) as total_harga,
                uang_dibayar,
                kembalian
            ')
            ->groupBy('order_id', 'customer_id', 'tipe_pembayaran', 'uang_dibayar', 'kembalian')
            ->orderBy('tanggal_pembelian', 'desc')
            ->paginate(5);

        // Get detailed items for each order
        $orders->getCollection()->transform(function ($order) {
            $order->items = Transaksi::with('barang')
                ->where('order_id', $order->order_id)
                ->where('status', 'selesai')
                ->get();
            $firstItem = $order->items->first();
            $order->customer = $firstItem ? $firstItem->customer : null;
            return $order;
        });

        // Filter options
        $allPaymentTypes = ['cash', 'transfer'];

        return view('owner.laporan-penjualan', compact('orders', 'allPaymentTypes'));
    }

    public function laporanBarangReturn(Request $request)
    {
        // Base query
        $query = Transaksi::whereIn('status', ['return', 'return_partial'])
            ->with('barang', 'customer');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($searchTerm) {
                      $customerQuery->where('nama_customer', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('barang', function($barangQuery) use ($searchTerm) {
                      $barangQuery->where('nama_barang', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhere('alasan_return', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by payment method
        if ($request->filled('tipe_pembayaran')) {
            $query->where('tipe_pembayaran', $request->tipe_pembayaran);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('updated_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('updated_at', '<=', $request->tanggal_sampai);
        }

        // Get all return transactions
        $allReturns = $query->orderBy('updated_at', 'desc')->get();

        // Group by order_id
        $groupedReturns = $allReturns->groupBy('order_id')->map(function ($items) {
            $firstItem = $items->first();
            
            // Create a single order object with all items
            $order = new \stdClass();
            $order->id = $firstItem->id;
            $order->order_id = $firstItem->order_id;
            $order->customer = $firstItem->customer;
            $order->tipe_pembayaran = $firstItem->tipe_pembayaran;
            $order->status = $firstItem->status;
            $order->alasan_return = $firstItem->alasan_return;
            $order->tanggal_pembelian = $firstItem->tanggal_pembelian;
            $order->updated_at = $firstItem->updated_at;
            $order->items = $items; // All items in this order
            $order->total_jumlah = $items->sum('jumlah');
            $order->total_harga = $items->sum('total_harga');
            
            return $order;
        })->values();

        // Paginate manually
        $perPage = 5;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedItems = $groupedReturns->slice($offset, $perPage)->values();
        
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $groupedReturns->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Filter options
        $allPaymentTypes = ['cash', 'transfer'];
        $allStatus = ['return', 'return_partial'];

        return view('transaksi.list_return', compact('orders', 'allPaymentTypes', 'allStatus'));
    }

    public function laporanPenjualanExport()
    {
        return Excel::download(new LaporanPenjualanExport, 'laporan_penjualan.xlsx');
    }

    public function chartData()
    {
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

        return response()->json([
            'penjualanPerBulan' => $penjualanPerBulan,
            'returnPerBulan' => $returnPerBulan,
            'categories' => $categories
        ]);
    }
}
