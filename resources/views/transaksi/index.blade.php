@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- Judul --}}
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Transaksi</h1>

    {{-- Link Kelola --}}
    <div class="mb-6 flex space-x-4">
        <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Kelola Barang</a>
        <a href="{{ route('customer.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Kelola Customer</a>
    </div>

    {{-- Pilih Customer --}}
    <form method="GET" action="{{ route('transaksi.index') }}" class="mb-6 flex items-center space-x-4">
        <label for="customer_id" class="font-semibold">Pilih Customer:</label>
        <select name="customer_id" id="customer_id" class="border border-gray-300 rounded px-3 py-2" onchange="this.form.submit()">
            <option value="">-- Pilih Customer --</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ (request('customer_id') == $c->id) ? 'selected' : '' }}>
                    {{ $c->nama_customer }} - {{ $c->no_hp }} - {{ ucfirst($c->tipe_pembeli) }}
                </option>
            @endforeach
        </select>
        <a href="{{ route('customer.create') }}" class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah Customer</a>
    </form>

    {{-- Customer Info --}}
    <div class="mb-6 border rounded p-4 bg-gray-50">
        <p><strong>Nama Customer:</strong> {{ $customer->nama_customer ?? '-' }}</p>
        <p><strong>No HP:</strong> {{ $customer->no_hp ?? '-' }}</p>
        <p><strong>Tipe Customer:</strong> {{ $customer->tipe_pembeli ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $customer->alamat ?? '-' }}</p>
    </div>

    {{-- Search Barang --}}
    <form method="GET" action="{{ route('transaksi.index') }}" class="mb-4 flex space-x-2">
        <input type="hidden" name="customer_id" value="{{ request('customer_id') }}" />
        <input type="text" name="search_barang" placeholder="Cari Barang" value="{{ request('search_barang') }}"
            class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Cari Barang</button>
    </form>

    {{-- Search Results --}}
    @if(isset($searchResults) && $searchResults->count() > 0)
        <div class="mb-6 p-4 border rounded bg-white">
            <h2 class="mb-2 font-semibold">Hasil Pencarian</h2>
            @foreach($searchResults as $barang)
                <div class="mb-2 p-2 border rounded flex items-center space-x-4">
                    <div class="w-24 h-24 bg-gray-200 rounded overflow-hidden flex items-center justify-center">
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="object-cover w-full h-full" />
                        @else
                            <span class="text-gray-500">No Image</span>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <p><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
                        <p><strong>Harga Grosir & Bengkel:</strong> Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</p>
                        <p><strong>Harga:</strong> Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('transaksi.addToCart') }}" class="mt-2 flex items-center space-x-2">
                            @csrf
                            <input type="hidden" name="barang_id" value="{{ $barang->id }}" />
                            <div class="flex items-center space-x-4 mr-4">
                                <label class="flex items-center">
                                    <input type="radio" name="tipe_harga" value="biasa" checked class="mr-1">
                                    Harga Biasa
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="tipe_harga" value="grosir" class="mr-1">
                                    Harga Grosir
                                </label>
                            </div>
                            <label for="jumlah_{{ $barang->id }}" class="mr-2">Jumlah:</label>
                            <input type="number" id="jumlah_{{ $barang->id }}" name="jumlah" value="1" min="1" class="w-20 border border-gray-300 rounded px-2 py-1" />
                            <button type="submit" class="px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700">Tambah Barang Ke Daftar Belanja</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Daftar Belanja --}}
    <div class="mb-6 border rounded p-4 bg-white">
        <h2 class="mb-4 font-semibold">Daftar Belanja</h2>
        @if(session('cart') && count(session('cart')) > 0)
            <table class="w-full border-collapse mb-4">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Barang</th>
                        <th class="border px-4 py-2">Tipe Harga</th>
                        <th class="border px-4 py-2">Jumlah</th>
                        <th class="border px-4 py-2">Harga/Pcs</th>
                        <th class="border px-4 py-2">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach(session('cart') as $item)
                        @php
                            $itemTotal = $item['harga'] * $item['jumlah'];
                            $total += $itemTotal;
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ $item['nama_barang'] }}</td>
                            <td class="border px-4 py-2">{{ ucfirst($item['tipe_harga']) }}</td>
                            <td class="border px-4 py-2">{{ $item['jumlah'] }}</td>
                            <td class="border px-4 py-2">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td class="border px-4 py-2">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right font-semibold">Total Rp {{ number_format($total, 0, ',', '.') }}</div>
        @else
            <p>Daftar belanja kosong.</p>
        @endif
    </div>

    {{-- Konfirmasi Pesanan --}}
    <form method="POST" action="{{ route('transaksi.confirmOrder') }}">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer->id ?? '' }}" />
        <div class="mb-4">
            <label for="tipe_pembayaran" class="block mb-1 font-medium">Pilih Tipe Pembayaran</label>
            <select name="tipe_pembayaran" id="tipe_pembayaran" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">Pilih Tipe Pembayaran</option>
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
                <option value="kredit">Kredit</option>
            </select>
        </div>
        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Konfirmasi Pesanan</button>
    </form>
</div>
@endsection
