<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::all();
        $customer = null;
        $query = Barang::query();
        $cart = session('cart', []);

        // Filter barang berdasarkan search
        if ($request->filled('search_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->search_barang . '%');
        }

        // Filter barang berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $barangs = $query->active()->orderBy('created_at', 'desc')->get();

        // Get all available categories (not just from existing barangs)
        $allCategories = [
            'Sepeda Pacifik',
            'Sepeda Listrik',
            'Ban',
            'Sepeda Stroller',
            'Sparepart',
            'Lainnya'
        ];

        // Store selected customer_id in session if provided
        if ($request->filled('customer_id')) {
            session(['selected_customer_id' => $request->customer_id]);
            $customer = Customer::find($request->customer_id);
        } elseif (session()->has('selected_customer_id')) {
            $customer = Customer::find(session('selected_customer_id'));
        }

        return view('transaksi.index', compact('customers', 'customer', 'barangs', 'cart', 'allCategories'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Get customer from session
        $customerId = session('selected_customer_id');
        if (!$customerId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Pilih customer terlebih dahulu'], 400);
            }
            return redirect()->route('transaksi.index')->withErrors(['customer' => 'Pilih customer terlebih dahulu']);
        }

        $customer = Customer::findOrFail($customerId);

        // Auto-determine tipe_harga based on customer type
        $tipe_harga = 'biasa'; // default
        if (in_array($customer->tipe_pembeli, ['bengkel_langganan', 'bengkel', 'langganan'])) {
            $tipe_harga = 'grosir';
        }

        $cart = session('cart', []);

        // Determine harga based on auto-determined tipe_harga
        $harga = $tipe_harga === 'grosir' ? $barang->harga_grosir : $barang->harga;

        // Check if barang with same tipe_harga already in cart
        $found = false;
        $previousQuantity = 0;
        foreach ($cart as &$item) {
            if ($item['barang_id'] == $barang->id && $item['tipe_harga'] == $tipe_harga) {
                $previousQuantity = $item['jumlah'];
                $item['jumlah'] += $request->jumlah;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = [
                'barang_id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'harga' => $harga,
                'tipe_harga' => $tipe_harga,
                'jumlah' => $request->jumlah,
                'gambar' => $barang->gambar, // Add image field
            ];
        }

        session(['cart' => $cart]);

        // Prepare response data
        $addedItem = [
            'nama_barang' => $barang->nama_barang,
            'jumlah' => $request->jumlah,
            'harga' => $harga,
            'tipe_harga' => $tipe_harga,
            'total_harga' => $harga * $request->jumlah,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $found ? $previousQuantity + $request->jumlah : $request->jumlah,
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan ke daftar belanja',
                'item' => $addedItem,
                'cart_count' => count($cart)
            ]);
        }

        // Redirect back to transaksi index with customer_id
        $selectedCustomerId = session('selected_customer_id', null);
        
        if ($selectedCustomerId) {
            return redirect()->route('transaksi.index', ['customer_id' => $selectedCustomerId])
                ->with('success', 'Barang berhasil ditambahkan ke daftar belanja');
        }
        
        return redirect()->route('transaksi.index')
            ->with('success', 'Barang berhasil ditambahkan ke daftar belanja');
    }

    public function removeFromCart($index)
    {
        $cart = session('cart', []);
        if (isset($cart[$index])) {
            unset($cart[$index]);
            $cart = array_values($cart); // Reindex array
            session(['cart' => $cart]);
            
            // Check if it's an AJAX request
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Barang berhasil dihapus dari daftar belanja'
                ]);
            }
        } else {
            // If item not found and it's AJAX request
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }
        }

        return redirect()->route('transaksi.index')->with('success', 'Barang berhasil dihapus dari daftar belanja');
    }

    public function updateCartQuantity(Request $request, $index)
    {
        $request->validate([
            'action' => 'required|in:increase,decrease',
        ]);

        $cart = session('cart', []);
        if (!isset($cart[$index])) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }

        if ($request->action === 'increase') {
            $cart[$index]['jumlah'] += 1;
        } elseif ($request->action === 'decrease') {
            if ($cart[$index]['jumlah'] > 1) {
                $cart[$index]['jumlah'] -= 1;
            } else {
                unset($cart[$index]);
                $cart = array_values($cart);
            }
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tipe_pembayaran' => 'required|string',
            'uang_dibayar' => 'required|numeric|gte:0',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Daftar belanja kosong'], 400);
            }
            return redirect()->route('transaksi.index')->withErrors(['cart' => 'Daftar belanja kosong']);
        }

        $customer = Customer::findOrFail($request->customer_id);

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        // Check if uang_dibayar is sufficient
        $uang_dibayar = $request->uang_dibayar;
        if ($uang_dibayar < $total) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Uang yang dibayar tidak cukup. Total yang harus dibayar: Rp ' . number_format($total, 0, ',', '.')]);
            }
            return redirect()->back()->withErrors(['uang_dibayar' => 'Uang yang dibayar tidak cukup. Total yang harus dibayar: Rp ' . number_format($total, 0, ',', '.')]);
        }

        // Calculate change
        $kembalian = $uang_dibayar - $total;

        try {
            // Generate unique order ID for this transaction
            $orderId = 'ORD-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $orderIds = [];
            DB::transaction(function () use ($cart, $customer, $request, $uang_dibayar, $kembalian, $orderId, &$orderIds) {
                foreach ($cart as $item) {
                    $barang = Barang::findOrFail($item['barang_id']);
                    if ($barang->stok < $item['jumlah']) {
                        throw new \Exception("Stok barang {$barang->nama_barang} tidak cukup");
                    }

                    $total_harga = $item['harga'] * $item['jumlah'];

                    $barangModel = Barang::findOrFail($item['barang_id']);
                    $transaksi = Transaksi::create([
                        'order_id' => $orderId,
                        'customer_id' => $customer->id,
                        'barang_id' => $barang->id,
                        'jumlah' => $item['jumlah'],
                        'harga_barang' => $item['harga'],

                        'total_harga' => $total_harga,
                        'uang_dibayar' => $uang_dibayar,
                        'kembalian' => $kembalian,
                        'tanggal_pembelian' => now(),
                        'tipe_pembayaran' => $request->tipe_pembayaran,
                        // 'alamat_pengantaran' => $customer->alamat, // Removed
                        'status' => 'selesai',
                    ]);

                    $orderIds[] = $transaksi->id;

                    $barang->decrement('stok', $item['jumlah']);
                }
            });

            session(['last_order_ids' => $orderIds]);
            session()->forget('cart');
            session()->forget('selected_customer_id');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dikonfirmasi',
                    'redirect' => route('transaksi.confirm')
                ]);
            }

            return redirect()->route('transaksi.confirm')->with('success', 'Pesanan berhasil dikonfirmasi');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function confirm()
    {
        $orderIds = session('last_order_ids', []);
        if (empty($orderIds)) {
            return redirect()->route('transaksi.index')->withErrors('Tidak ada pesanan untuk dikonfirmasi');
        }

        $transaksis = Transaksi::with('barang', 'customer')->whereIn('id', $orderIds)->get();
        $customer = $transaksis->first()->customer ?? null;
        $total = $transaksis->sum('total_harga');

        return view('transaksi.confirm', compact('transaksis', 'customer', 'total'));
    }

    public function exportPdf()
    {
        $orderIds = session('last_order_ids', []);
        if (empty($orderIds)) {
            return redirect()->route('transaksi.index')->withErrors('Tidak ada pesanan untuk diekspor');
        }

        $transaksis = Transaksi::with('barang', 'customer')->whereIn('id', $orderIds)->get();
        $customer = $transaksis->first()->customer ?? null;
        $total = $transaksis->sum('total_harga');

        $pdf = Pdf::loadView('transaksi.nota_pdf', compact('transaksis', 'customer', 'total'));

        return $pdf->download('nota_pembelian.pdf');
    }

    public function listReturnableTransaksi(Request $request)
    {
        // Base query
        $query = Transaksi::where('status', 'selesai')
            ->whereNotNull('order_id')
            ->where('order_id', '!=', '');

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

        // Group transactions by order_id and aggregate data
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
        $orders->transform(function ($order) {
            $order->items = Transaksi::with('barang', 'customer')
                ->where('order_id', $order->order_id)
                ->where('status', 'selesai')
                ->get();
            $order->customer = $order->items->first()->customer;
            return $order;
        });

        // Filter options
        $allPaymentTypes = ['cash', 'transfer'];

        return view('transaksi.list_return', compact('orders', 'allPaymentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tipe_pembayaran' => 'required|string',
            'tanggal_pembelian' => 'required|date',
            // 'alamat_pengantaran' => 'nullable|string', // Removed
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Validasi stok cukup
        if ($request->jumlah > $barang->stok) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah' => 'Stok barang tidak cukup. Stok tersedia: ' . $barang->stok]);
        }

        DB::transaction(function () use ($request, $barang) {
            // Generate unique order ID for this transaction
            $orderId = 'ORD-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $harga_barang = $barang->harga;
            $jumlah = $request->jumlah;

            $total_harga = $harga_barang * $jumlah;

            // Simpan transaksi
            Transaksi::create([
                'order_id' => $orderId,
                'customer_id' => $request->customer_id,
                'barang_id' => $request->barang_id,
                'jumlah' => $jumlah,
                'harga_barang' => $harga_barang,

                'total_harga' => $total_harga,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'tipe_pembayaran' => $request->tipe_pembayaran,
                // 'alamat_pengantaran' => $request->alamat_pengantaran, // Removed
                'status' => 'selesai', // default
            ]);

            // Kurangi stok barang
            $barang->decrement('stok', $jumlah);
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan');
    }



    public function barangReturn($id)
    {
        $transaksi = Transaksi::with('barang', 'customer')->findOrFail($id);

        // Get all transactions in the same order
        $orderTransaksis = Transaksi::with('barang', 'customer')
            ->where('order_id', $transaksi->order_id)
            ->where('status', 'selesai')
            ->get();

        return view('transaksi.return', compact('transaksi', 'orderTransaksis'));
    }

    public function return(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.transaksi_id' => 'required|exists:transaksis,id',
            'items.*.return' => 'boolean',
            'items.*.jumlah_return' => 'nullable|integer|min:0',
            'alasan_return' => 'nullable|string|max:255',
        ]);

        // Get the main transaksi to get order_id
        $mainTransaksi = Transaksi::with('barang')->findOrFail($id);

        if ($mainTransaksi->status !== 'selesai') {
            return redirect()->back()->withErrors(['transaksi_id' => 'Transaksi tidak dapat dikembalikan']);
        }

        DB::transaction(function () use ($request, $mainTransaksi) {
            $updatedTransaksis = [];
            foreach ($request->items as $item) {
                if (!empty($item['return']) && $item['return'] && $item['jumlah_return'] > 0) {
                    // Find the specific transaksi for this item
                    $transaksi = Transaksi::with('barang')->findOrFail($item['transaksi_id']);
                    
                    // Validate this transaksi belongs to the same order and is eligible for return
                    if ($transaksi->order_id !== $mainTransaksi->order_id || $transaksi->status !== 'selesai') {
                        continue; // Skip this item
                    }
                    
                    $jumlahReturn = $item['jumlah_return'];
                    if ($jumlahReturn > $transaksi->jumlah) {
                        throw new \Exception('Jumlah return tidak boleh lebih dari jumlah pembelian');
                    }
                    
                    $barang = $transaksi->barang;
                    if ($barang) {
                        $barang->increment('stok', $jumlahReturn);
                    }
                    
                    // Update status and handle partial/full return
                    if ($jumlahReturn == $transaksi->jumlah) {
                        // Full return
                        $transaksi->status = 'return';
                        $transaksi->alasan_return = $request->alasan_return;
                        $transaksi->save();
                        $updatedTransaksis[] = $transaksi;
                    } else {
                        // For partial return, keep original transaction for remaining quantity
                        // and create new transaction for returned items
                        $originalJumlah = $transaksi->jumlah;
                        $remaining = $originalJumlah - $jumlahReturn;

                        // Update original transaction with remaining quantity
                        $transaksi->jumlah = $remaining;
                        $transaksi->total_harga = $transaksi->harga_barang * $remaining;
                        $transaksi->status = 'selesai'; // Keep the original transaction as 'selesai'
                        $transaksi->save();

                        // Create new transaction for returned items
                        $returnTransaksi = Transaksi::create([
                            'order_id' => $transaksi->order_id,
                            'customer_id' => $transaksi->customer_id,
                            'barang_id' => $transaksi->barang_id,
                            'jumlah' => $jumlahReturn,
                            'harga_barang' => $transaksi->harga_barang,
                            'total_harga' => $transaksi->harga_barang * $jumlahReturn,
                            'uang_dibayar' => $transaksi->uang_dibayar,
                            'kembalian' => $transaksi->kembalian,
                            'tanggal_pembelian' => $transaksi->tanggal_pembelian,
                            'tipe_pembayaran' => $transaksi->tipe_pembayaran,
                            // 'alamat_pengantaran' => $transaksi->alamat_pengantaran, // Removed
                            'status' => 'return_partial',
                            'parent_transaksi_id' => $transaksi->id,
                            'alasan_return' => $request->alasan_return,
                        ]);
                        $updatedTransaksis[] = $returnTransaksi;
                    }
                }
            }

            // After processing all returns, check if the order has any remaining 'selesai' transactions
            // If yes, change any 'return' status to 'return_partial' for consistency
            $orderId = $mainTransaksi->order_id;
            $hasRemainingItems = Transaksi::where('order_id', $orderId)->where('status', 'selesai')->exists();
            if ($hasRemainingItems) {
                foreach ($updatedTransaksis as $updatedTransaksi) {
                    if ($updatedTransaksi->status === 'return') {
                        $updatedTransaksi->status = 'return_partial';
                        $updatedTransaksi->save();
                    }
                }
            }
        });

        $user = Auth::user();
        $redirectRoute = $user && $user->role == 'owner' ? 'owner.laporanBarangReturn' : 'transaksi.listReturnable';
        return redirect()->route($redirectRoute)->with('success', 'Barang berhasil dikembalikan');
    }
}
