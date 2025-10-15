<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'tipe_harga' => 'required|in:biasa,grosir',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        $cart = session('cart', []);

        // Determine harga based on tipe_harga and apply discount
        $harga = $request->tipe_harga === 'grosir' ? $barang->harga_grosir : $barang->harga;
        $harga = $harga * (100 - $barang->diskon) / 100;

        // Check if barang with same tipe_harga already in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['barang_id'] == $barang->id && $item['tipe_harga'] == $request->tipe_harga) {
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
                'tipe_harga' => $request->tipe_harga,
                'jumlah' => $request->jumlah,
            ];
        }

        session(['cart' => $cart]);

        // Redirect with selected_customer_id from session to keep customer selected
        $selectedCustomerId = session('selected_customer_id', null);

        return redirect()->route('transaksi.index', ['customer_id' => $selectedCustomerId])->with('success', 'Barang berhasil ditambahkan ke daftar belanja');
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tipe_pembayaran' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('transaksi.index')->withErrors(['cart' => 'Daftar belanja kosong']);
        }

        $customer = Customer::findOrFail($request->customer_id);

        $orderIds = [];
        DB::transaction(function () use ($cart, $customer, $request, &$orderIds) {
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
                    'diskon' => $barangModel->diskon,
                    'total_harga' => $total_harga,
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

        return redirect()->route('transaksi.confirm')->with('success', 'Pesanan berhasil dikonfirmasi');
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
            'diskon' => 'nullable|numeric|min:0|max:100',
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
            $diskon = $request->diskon ?? 0;

            $total_harga = $harga_barang * $jumlah * (1 - $diskon / 100);

            // Simpan transaksi
            Transaksi::create([
                'customer_id' => $request->customer_id,
                'barang_id' => $request->barang_id,
                'jumlah' => $jumlah,
                'harga_barang' => $harga_barang,
                'diskon' => $diskon,
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
                    // Update status based on return quantity
                    if ($jumlahReturn == $transaksi->jumlah) {
                        $transaksi->status = 'return';
                    } else {
                        $transaksi->status = 'return_partial';
                    }
                    $transaksi->save();
                }
            }
            // Save alasan return if needed (not stored in current schema)
        });

        return redirect()->route('transaksi.barangReturn', ['id' => $transaksi->id])->with('success', 'Barang berhasil dikembalikan');
    }
}
