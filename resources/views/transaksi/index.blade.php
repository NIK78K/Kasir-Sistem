@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    {{-- Banner Header --}}
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 p-6 shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Transaksi Penjualan
        </h1>
    </div>

    {{-- Link Kelola --}}
    <div class="mb-8 flex gap-3">
        <a href="{{ route('barang.index') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Kelola Barang
        </a>
        <a href="{{ route('customer.index') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Kelola Customer
        </a>
    </div>

    {{-- Pilih Customer --}}
    <div class="mb-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Pilih Customer
        </h2>
        <form method="GET" action="{{ route('transaksi.index') }}" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
            <select name="customer_id" id="customer_id" class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition bg-white" onchange="this.form.submit()">
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ (request('customer_id') == $c->id) ? 'selected' : '' }}>
                        {{ $c->nama_customer }} - {{ $c->no_hp }} - {{ ucfirst($c->tipe_pembeli) }}
                    </option>
                @endforeach
            </select>
            <a href="{{ route('customer.create') }}" class="px-5 py-2.5 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition font-semibold whitespace-nowrap flex items-center gap-2 justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Customer
            </a>
        </form>
    </div>

    {{-- Customer Info --}}
    <div class="mb-8 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Informasi Customer
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div>
                    <p class="text-xs text-gray-600">Nama Customer</p>
                    <p class="font-semibold text-gray-900">{{ $customer->nama_customer ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <div>
                    <p class="text-xs text-gray-600">No HP</p>
                    <p class="font-semibold text-gray-900">{{ $customer->no_hp ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <div>
                    <p class="text-xs text-gray-600">Tipe Customer</p>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ ($customer->tipe_pembeli ?? '') == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($customer->tipe_pembeli ?? '-') }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                    <p class="text-xs text-gray-600">Alamat</p>
                    <p class="font-semibold text-gray-900">{{ $customer->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Barang --}}
    {{-- Remove search barang section as per request --}}
    {{-- <div class="mb-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Cari Barang
        </h2>
        <form method="GET" action="{{ route('transaksi.index') }}" class="flex gap-3">
            <input type="hidden" name="customer_id" value="{{ request('customer_id') }}" />
            <input type="text" name="search_barang" placeholder="Masukkan nama barang..." value="{{ request('search_barang') }}"
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 transition" />
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition font-semibold whitespace-nowrap">
                Cari Barang
            </button>
        </form>
    </div> --}}

    {{-- Daftar Barang --}}
    @if($barangs->count() > 0)
        <div class="mb-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Daftar Barang
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($barangs as $barang)
                    <div class="border rounded-xl p-6 flex flex-col space-y-4 shadow-md hover:shadow-2xl transition-all duration-300 bg-white hover:bg-indigo-600 hover:text-white hover:-translate-y-2 hover:scale-105 group">
                        <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center group-hover:bg-indigo-700 relative">
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="object-cover w-full h-full group-hover:brightness-110" />
                            @else
                                <span class="text-gray-400 italic group-hover:text-white">No Image</span>
                            @endif
                            @if($barang->diskon > 0)
                                <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                    -{{ $barang->diskon }}%
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow flex flex-col justify-between">
                            <div class="space-y-2">
                                <p class="text-lg font-bold text-gray-900 group-hover:text-white line-clamp-2">{{ $barang->nama_barang }}</p>
                                <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Harga Grosir & Bengkel:</span> 
                                    @if($barang->diskon > 0)
                                        <span class="line-through">Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</span>
                                        <span class="ml-2 font-bold text-green-400">Rp {{ number_format($barang->harga_grosir * (100 - $barang->diskon) / 100, 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Harga:</span> 
                                    @if($barang->diskon > 0)
                                        <span class="line-through">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                                        <span class="ml-2 font-bold text-green-400">Rp {{ number_format($barang->harga * (100 - $barang->diskon) / 100, 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Stok:</span> <span class="text-green-600 font-semibold group-hover:text-green-200">{{ $barang->stok }}</span></p>
                                <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Kategori:</span> {{ $barang->kategori }}</p>
                            </div>
                            <div class="mt-4">
                                <form method="POST" action="{{ route('transaksi.addToCart') }}" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="barang_id" value="{{ $barang->id }}" />
                                    <div class="flex flex-wrap gap-4 items-center">
                                        <div class="flex items-center gap-4">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="tipe_harga" value="biasa" checked class="w-4 h-4 text-blue-600">
                                                <span class="text-sm font-medium">Harga Eceran</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="tipe_harga" value="grosir" class="w-4 h-4 text-blue-600">
                                                <span class="text-sm font-medium">Harga Grosir</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <label for="jumlah_{{ $barang->id }}" class="text-sm font-medium">Jumlah:</label>
                                            <input type="number" id="jumlah_{{ $barang->id }}" name="jumlah" value="1" min="1" class="w-20 border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-600" />
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p class="text-center text-gray-600">Tidak ada barang tersedia.</p>
    @endif

    {{-- Daftar Belanja --}}
    <div class="mb-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Daftar Belanja
        </h2>
        @if(session('cart') && count(session('cart')) > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-800">Barang</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-800">Tipe Harga</th>
                            <th class="border border-gray-300 px-4 py-3 text-center font-semibold text-gray-800">Jumlah</th>
                            <th class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-800">Harga/Pcs</th>
                            <th class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-800">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $item)
                            @php
                                $itemTotal = $item['harga'] * $item['jumlah'];
                                $total += $itemTotal;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border border-gray-300 px-4 py-3 font-medium">{{ $item['nama_barang'] }}</td>
                                <td class="border border-gray-300 px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item['tipe_harga'] == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ ucfirst($item['tipe_harga']) }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-center font-semibold">{{ $item['jumlah'] }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-right">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-4 py-3 text-right font-bold text-blue-600">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-end">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg px-6 py-3">
                    <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-gray-500 font-medium">Daftar belanja kosong</p>
                <p class="text-gray-400 text-sm mt-1">Silakan cari dan tambahkan barang ke keranjang</p>
            </div>
        @endif
    </div>

    {{-- Konfirmasi Pesanan --}}
    <form method="POST" action="{{ route('transaksi.confirmOrder') }}" class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer->id ?? '' }}" />
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Metode Pembayaran
        </h2>
        <div class="mb-6">
            <select name="tipe_pembayaran" id="tipe_pembayaran" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition bg-white font-medium" required>
                <option value="">Pilih Tipe Pembayaran</option>
                <option value="cash">💵 Cash</option>
                <option value="transfer">🏦 Transfer Bank</option>
                <option value="kredit">💳 Kredit</option>
            </select>
        </div>
        <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-bold text-lg shadow-lg flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Konfirmasi Pesanan
        </button>
    </form>
</div>
@endsection
