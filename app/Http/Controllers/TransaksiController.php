<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        $customers = Customer::all();
        $transaksis = Transaksi::with('barang', 'customer')
            ->where('status', 'selesai') // hanya transaksi aktif
            ->latest()
            ->paginate(10);

        return view('transaksi.index', compact('barangs', 'customers', 'transaksis'));
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

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan');
    }

    public function listBatal()
    {
        $transaksis = Transaksi::with('barang', 'customer')
            ->where('status', 'batal')
            ->latest()
            ->paginate(10);

        return view('transaksi.batal', compact('transaksis'));
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
