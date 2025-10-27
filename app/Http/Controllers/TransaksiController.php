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

        $barangs = $query->orderBy('created_at', 'desc')->get();

        // Store selected customer_id in session if provided
        if ($request->filled('customer_id')) {
            session(['selected_customer_id' => $request->customer_id]);
            $customer = Customer::find($request->customer_id);
        } elseif (session()->has('selected_customer_id')) {
            $customer = Customer::find(session('selected_customer_id'));
        }

        return view('transaksi.index', compact('customers', 'customer', 'barangs', 'cart'));
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
        if (in_array($customer->tipe_pembeli, ['bengkel', 'langganan'])) {
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

        // Redirect with selected_customer_id from session to keep customer selected
        $selectedCustomerId = session('selected_customer_id', null);

        return redirect()->route('transaksi.index', ['customer_id' => $selectedCustomerId] + (count($cart) > 0 ? ['#daftar-belanja'] : []))->with('success', 'Barang berhasil ditambahkan ke daftar belanja');
    }

    public function removeFromCart($index)
    {
        $cart = session('cart', []);
        if (isset($cart[$index])) {
            unset($cart[$index]);
            $cart = array_values($cart); // Reindex array
            session(['cart' => $cart]);
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
            $orderIds = [];
            DB::transaction(function () use ($cart, $customer, $request, $uang_dibayar, $kembalian, &$orderIds) {
                foreach ($cart as $item) {
                    $barang = Barang::findOrFail($item['barang_id']);
                    if ($barang->stok < $item['jumlah']) {
                        throw new \Exception("Stok barang {$barang->nama_barang} tidak cukup");
                    }

                    $total_harga = $item['harga'] * $item['jumlah'];

                    $barangModel = Barang::findOrFail($item['barang_id']);
                    $transaksi = Transaksi::create([
                        'customer_id' => $customer->id,
                        'barang_id' => $barang->id,
                        'jumlah' => $item['jumlah'],
                        'harga_barang' => $item['harga'],

                        'total_harga' => $total_harga,
                        'uang_dibayar' => $uang_dibayar,
                        'kembalian' => $kembalian,
                        'tanggal_pembelian' => now(),
                        'tipe_pembayaran' => $request->tipe_pembayaran,
                        'alamat_pengantaran' => $customer->alamat,
                        'status' => 'selesai',
                    ]);

                    $orderIds[] = $transaksi->id;

                    $barang->decrement('stok', $item['jumlah']);
                }
            });

            session(['last_order_ids' => $orderIds]);
            session()->forget('cart');

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

    public function listReturnableTransaksi()
    {
        $transaksis = Transaksi::with('barang', 'customer')
            ->where('status', 'selesai')
            ->latest()
            ->paginate(10);

        return view('transaksi.list_return', compact('transaksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tipe_pembayaran' => 'required|string',
            'tanggal_pembelian' => 'required|date',
            'alamat_pengantaran' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Validasi stok cukup
        if ($request->jumlah > $barang->stok) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jumlah' => 'Stok barang tidak cukup. Stok tersedia: ' . $barang->stok]);
        }

        DB::transaction(function () use ($request, $barang) {
            $harga_barang = $barang->harga;
            $jumlah = $request->jumlah;

            $total_harga = $harga_barang * $jumlah;

            // Simpan transaksi
            Transaksi::create([
                'customer_id' => $request->customer_id,
                'barang_id' => $request->barang_id,
                'jumlah' => $jumlah,
                'harga_barang' => $harga_barang,

                'total_harga' => $total_harga,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'tipe_pembayaran' => $request->tipe_pembayaran,
                'alamat_pengantaran' => $request->alamat_pengantaran,
                'status' => 'selesai', // default
            ]);

            // Kurangi stok barang
            $barang->decrement('stok', $jumlah);
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan');
    }

    public function batal(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'confirm_batal' => 'required|accepted',
        ]);

        $transaksi = Transaksi::findOrFail($request->transaksi_id);

        DB::transaction(function () use ($transaksi) {
            // Kembalikan stok barang
            $barang = $transaksi->barang;
            if ($barang) {
                $barang->increment('stok', $transaksi->jumlah);
            }

            // Ubah status transaksi menjadi batal
            $transaksi->status = 'batal';
            $transaksi->save();
        });

        return redirect()->route('transaksi.listBatal')->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan');
    }

    public function listBatal($id = null)
    {
        if ($id) {
            $transaksi = Transaksi::with('barang', 'customer')
                ->where('id', $id)
                ->where('status', 'selesai') // Only allow cancellation of completed transactions
                ->firstOrFail();

            return view('transaksi.batal', compact('transaksi'));
        } else {
            $transaksis = Transaksi::with('barang', 'customer')
                ->where('status', 'selesai') // Show completed transactions that can be canceled
                ->latest()
                ->paginate(10);

            return view('transaksi.batal', compact('transaksis'));
        }
    }

    public function barangReturn($id)
    {
        $transaksi = Transaksi::with('barang', 'customer')->findOrFail($id);

        return view('transaksi.return', compact('transaksi'));
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

        $transaksi = Transaksi::with('barang')->findOrFail($id);

        if ($transaksi->status !== 'selesai') {
            return redirect()->back()->withErrors(['transaksi_id' => 'Transaksi tidak dapat dikembalikan']);
        }

        DB::transaction(function () use ($request, $transaksi) {
            foreach ($request->items as $item) {
                if (!empty($item['return']) && $item['return'] && $item['jumlah_return'] > 0) {
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
                        $transaksi->save();
                    } else {
                        // Partial return: create new transaction for remaining quantity
                        $remaining = $transaksi->jumlah - $jumlahReturn;
                        Transaksi::create([
                            'customer_id' => $transaksi->customer_id,
                            'barang_id' => $transaksi->barang_id,
                            'jumlah' => $remaining,
                            'harga_barang' => $transaksi->harga_barang,
                            'total_harga' => $transaksi->harga_barang * $remaining,
                            'uang_dibayar' => $transaksi->uang_dibayar,
                            'kembalian' => $transaksi->kembalian,
                            'tanggal_pembelian' => $transaksi->tanggal_pembelian,
                            'tipe_pembayaran' => $transaksi->tipe_pembayaran,
                            'alamat_pengantaran' => $transaksi->alamat_pengantaran,
                            'status' => 'selesai',
                        ]);
                        // Update original transaction for returned quantity
                        $transaksi->jumlah = $jumlahReturn;
                        $transaksi->total_harga = $transaksi->harga_barang * $jumlahReturn;
                        $transaksi->status = 'return_partial';
                        $transaksi->save();
                    }
                }
            }
            // Save alasan return if needed (not stored in current schema)
        });

        $user = Auth::user();
        $redirectRoute = $user && $user->role == 'owner' ? 'owner.laporanBarangReturn' : 'transaksi.listReturnable';
        return redirect()->route($redirectRoute)->with('success', 'Barang berhasil dikembalikan');
    }
}
