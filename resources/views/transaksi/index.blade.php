@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- Judul --}}
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">💳 Manajemen Transaksi</h1>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-300">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Card Form Tambah Transaksi --}}
    <div class="bg-white shadow-lg rounded-2xl mb-6 overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4">
            <h3 class="text-lg font-semibold text-white">Tambah Transaksi</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama Pembeli --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Pembeli</label>
                    <select name="customer_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->nama_customer }} ({{ ucfirst($customer->tipe_pembeli) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Barang --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Barang</label>
                    <select name="barang_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} - Rp {{ number_format($barang->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Grid Input --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Jumlah Barang</label>
                        <input type="number" name="jumlah" min="1" value="{{ old('jumlah', 1) }}" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Diskon (%)</label>
                        <input type="number" name="diskon" min="0" max="100" value="{{ old('diskon', 0) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Tanggal Pembelian</label>
                        <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Tipe Pembayaran --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Tipe Pembayaran</label>
                    <select name="tipe_pembayaran" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500" required>
                        <option value="cash" {{ old('tipe_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ old('tipe_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="kredit" {{ old('tipe_pembayaran') == 'kredit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Alamat Pengantaran</label>
                    <textarea name="alamat_pengantaran" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">{{ old('alamat_pengantaran') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="pt-2">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-700 to-gray-900 p-4">
            <h3 class="text-lg font-semibold text-white">📋 Daftar Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Pembeli</th>
                        <th class="px-4 py-2 border">Barang</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Harga</th>
                        <th class="px-4 py-2 border">Diskon</th>
                        <th class="px-4 py-2 border">Total</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Pembayaran</th>
                        <th class="px-4 py-2 border">Alamat</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($transaksis as $transaksi)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 border text-center">{{ $transaksi->id }}</td>
                            <td class="px-4 py-2 border">{{ $transaksi->customer->nama_customer }}</td>
                            <td class="px-4 py-2 border">{{ $transaksi->barang->nama_barang }}</td>
                            <td class="px-4 py-2 border text-center">{{ $transaksi->jumlah }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border text-center">{{ $transaksi->diskon }}%</td>
                            <td class="px-4 py-2 border font-semibold text-green-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">{{ $transaksi->tanggal_pembelian->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($transaksi->tipe_pembayaran) }}</td>
                            <td class="px-4 py-2 border">{{ $transaksi->alamat_pengantaran }}</td>
                            <td class="px-4 py-2 border text-center">
                                <form action="{{ route('transaksi.batal') }}" method="POST" onsubmit="return confirm('Yakin batalkan transaksi?')">
                                    @csrf
                                    <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                        Batal
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $transaksis->links() }}
        </div>
    </div>
</div>
@endsection
