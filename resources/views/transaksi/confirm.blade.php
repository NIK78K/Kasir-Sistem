@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Konfirmasi Pesanan</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Detail Customer</h2>
        <p><strong>Nama:</strong> {{ $customer->nama_customer ?? '-' }}</p>
        <p><strong>No HP:</strong> {{ $customer->no_hp ?? '-' }}</p>
        <p><strong>Tipe Customer:</strong> {{ $customer->tipe_pembeli ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $customer->alamat ?? '-' }}</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Detail Pesanan</h2>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Barang</th>
                    <th class="border px-4 py-2">Jumlah</th>
                    <th class="border px-4 py-2">Harga Satuan</th>
                    <th class="border px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $transaksi)
                    <tr>
                        <td class="border px-4 py-2">{{ $transaksi->barang->nama_barang }}</td>
                        <td class="border px-4 py-2">{{ $transaksi->jumlah }}</td>
                        <td class="border px-4 py-2">Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-right mt-4 font-bold text-lg">
            Total: Rp {{ number_format($total, 0, ',', '.') }}
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Detail Pembayaran</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="font-semibold">Uang Dibayar:</p>
                <p class="text-lg">Rp {{ number_format($transaksis->first()->uang_dibayar ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="font-semibold">Kembalian:</p>
                <p class="text-lg text-green-600">Rp {{ number_format($transaksis->first()->kembalian ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="flex space-x-4">
        <a href="{{ route('transaksi.exportPdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Download Nota PDF</a>
        <a href="{{ route('transaksi.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Kembali ke Transaksi</a>
    </div>
</div>
@endsection


