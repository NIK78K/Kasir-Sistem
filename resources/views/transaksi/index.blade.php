@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <div class="flex flex-col h-[calc(100vh-4rem)] overflow-hidden">
        <!-- Accessible page heading (hidden) -->
        <h1 class="sr-only">Transaksi</h1>
        {{-- Main Content: 2 Column Layout --}}
        <div class="flex-1 flex flex-col lg:flex-row gap-0 bg-gray-100 overflow-hidden">
            {{-- Left Column: Products Section - Scrollable --}}
            <div class="flex-1 overflow-y-auto bg-white order-1 lg:order-1 lg:h-full h-[45vh]">
                {{-- Search & Filter Section --}}
                <div class="p-4 lg:p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('transaksi.index') }}" id="filter-form" class="space-y-3">
                        <input type="hidden" name="customer_id" value="{{ request('customer_id') }}" />
                        
                        {{-- Customer Selection with + Button --}}
                        <div class="flex gap-2 items-center">
                            <select name="customer_id" id="customer_id"
                                class="flex-1 px-4 py-2.5 pr-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 transition bg-white"
                                onchange="this.form.submit()">
                                <option value="" disabled {{ !request('customer_id') ? 'selected' : '' }}>Pilih Customer</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->nama_customer }} ({{ ucfirst($c->tipe_pembeli) }})
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('customer.index') }}"
                                class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-semibold whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Customer Baru
                            </a>
                        </div>

                        {{-- Search Bar --}}
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search_barang" placeholder="Cari Barang" value="{{ request('search_barang') }}"
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>

                        {{-- Category Filter Pills --}}
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="filterCategory('')" 
                                class="category-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ !request('kategori') ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Semua Produk
                            </button>
                            @foreach($allCategories as $category)
                                <button type="button" onclick="filterCategory('{{ $category }}')" 
                                    class="category-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ request('kategori') == $category ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $category }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="kategori" id="kategori-input" value="{{ request('kategori') }}" />
                    </form>
                </div>

                {{-- Products Grid --}}
                <div class="p-4 lg:p-6 bg-gray-50">
                    @if ($barangs->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 lg:gap-4">
                            @foreach ($barangs as $barang)
                                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-xl transition-all duration-200 hover:border-gray-300 cursor-pointer group flex flex-col">
                                    {{-- Product Image/Icon --}}
                                    <div class="w-full aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg overflow-hidden flex items-center justify-center relative mb-3 group-hover:scale-105 transition-transform">
                                        @if ($barang->gambar)
                                            @php $webp = method_exists($barang, 'webpPath') ? $barang->webpPath() : null; @endphp
                                            <picture>
                                                @if($webp)
                                                    <source type="image/webp" srcset="{{ asset('storage/' . $webp) }}">
                                                @endif
                                                <img src="{{ asset('storage/' . $barang->gambar) }}"
                                                     alt="{{ $barang->nama_barang }}"
                                                     width="400" height="400" decoding="async" loading="lazy"
                                                     class="object-cover w-full h-full" />
                                            </picture>
                                        @else
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    {{-- Product Name (not a heading for semantics) --}}
                                    <p class="text-sm font-bold text-gray-900 mb-2 line-clamp-2 min-h-[2.5rem]">
                                        {{ $barang->nama_barang }}
                                    </p>
                                    
                                    {{-- Category Badge --}}
                                    <div class="mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-orange-100 text-orange-700">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $barang->kategori }}
                                        </span>
                                    </div>
                                    
                                    {{-- Stock Badge --}}
                                    <div class="mb-3">
                                        <span class="inline-flex items-center text-xs font-semibold {{ $barang->stok <= 5 ? 'text-red-600' : 'text-green-600' }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Stok: {{ $barang->stok }} pcs
                                        </span>
                                    </div>
                                    
                                    {{-- Pricing --}}
                                    <div class="space-y-1 mb-3 flex-grow">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">Retail:</span>
                                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">Grosir:</span>
                                            @if ($barang->harga_grosir)
                                                <span class="text-sm font-bold text-blue-600">Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Add to Cart Button --}}
                                    <form method="POST" action="{{ route('transaksi.addToCart') }}">
                                        @csrf
                                        <input type="hidden" name="barang_id" value="{{ $barang->id }}" />
                                        <input type="hidden" name="jumlah" value="1" />
                                        <button type="submit"
                                            class="w-full px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition font-bold text-sm shadow-md flex items-center justify-center gap-2 add-to-cart-btn"
                                            data-barang-id="{{ $barang->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="flex flex-col items-center justify-center py-16 px-4">
                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-700 mb-2">Barang Tidak Tersedia</h2>
                            @if(request('kategori'))
                                <p class="text-gray-500 text-center">
                                    Tidak ada produk dalam kategori <span class="font-semibold">"{{ request('kategori') }}"</span>.
                                </p>
                            @elseif(request('search_barang'))
                                <p class="text-gray-500 text-center">
                                    Tidak ditemukan produk dengan kata kunci <span class="font-semibold">"{{ request('search_barang') }}"</span>.
                                </p>
                            @else
                                <p class="text-gray-500 text-center">
                                    Belum ada produk yang tersedia untuk transaksi.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Cart & Checkout Section - Dark Theme --}}
            <div class="w-full lg:w-[420px] lg:flex-shrink-0 flex flex-col bg-gradient-to-b from-slate-700 via-slate-800 to-slate-900 order-2 lg:order-2 shadow-2xl h-[55vh] lg:h-full overflow-hidden">
                {{-- Cart Header --}}
                <div class="px-5 py-3 border-b border-slate-600/50 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Keranjang Belanja
                        </h2>
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                            {{ count(session('cart', [])) }} items
                        </span>
                    </div>
                </div>

                {{-- Customer Info --}}
                @if($customer)
                <div class="px-5 py-3 bg-slate-700/50 border-b border-slate-600/50 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-base shadow-lg">
                            {{ substr($customer->nama_customer, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-white">{{ $customer->nama_customer }}</p>
                            <p class="text-xs text-slate-300">{{ $customer->tipe_pembeli == 'bengkel_langganan' || $customer->tipe_pembeli == 'bengkel' || $customer->tipe_pembeli == 'langganan' ? 'Bengkel Langganan' : 'Pembeli' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Cart Items --}}
                <div class="flex-1 overflow-y-auto min-h-0" id="daftar-belanja">
                    @php $total = 0; @endphp
                    @if(count(session('cart', [])) > 0)
                        @foreach(session('cart', []) as $index => $item)
                            @php
                                $itemTotal = $item['harga'] * $item['jumlah'];
                                $total += $itemTotal;
                            @endphp
                            <div class="border-b border-slate-600/30 p-3 lg:p-4 hover:bg-slate-700/30 transition">
                                <div class="flex gap-2 lg:gap-3">
                                    {{-- Item Image --}}
                                    <div class="w-12 h-12 lg:w-16 lg:h-16 bg-slate-600/50 rounded-lg flex-shrink-0 overflow-hidden">
                                        @if(isset($item['gambar']) && $item['gambar'])
                                            @php 
                                                $webpCart = preg_replace('/\.(jpe?g|png)$/i', '.webp', $item['gambar']);
                                                $hasWebpCart = \Illuminate\Support\Facades\Storage::disk('public')->exists($webpCart);
                                            @endphp
                                            <picture>
                                                @if($hasWebpCart)
                                                    <source type="image/webp" srcset="{{ asset('storage/' . $webpCart) }}">
                                                @endif
                                                <img src="{{ asset('storage/' . $item['gambar']) }}" 
                                                     alt="{{ $item['nama_barang'] }}" 
                                                     width="64" height="64" decoding="async" loading="lazy"
                                                     class="w-full h-full object-cover"
                                                     onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'w-8 h-8 text-slate-400 m-auto\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'/></svg>'">
                                            </picture>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Item Details --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs lg:text-sm font-bold text-white mb-1 line-clamp-1">{{ $item['nama_barang'] }}</p>
                                        <div class="flex items-center gap-2 mb-1 lg:mb-2">
                                            <span class="px-1.5 lg:px-2 py-0.5 text-[10px] lg:text-xs font-bold rounded {{ $item['tipe_harga'] == 'grosir' ? 'bg-blue-500 text-white' : 'bg-green-500 text-white' }}">
                                                {{ ucfirst($item['tipe_harga']) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between mb-1 lg:mb-0">
                                            <span class="text-xs lg:text-sm text-slate-300">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                                            <span class="text-sm lg:text-base font-bold text-green-400">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2 lg:mt-3">
                                            {{-- Quantity Controls --}}
                                            <div class="flex items-center gap-1 lg:gap-2 bg-slate-600/50 rounded-lg p-0.5 lg:p-1">
                                                <button class="btn-decrease-qty w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center rounded-md bg-red-500 text-white hover:bg-red-600 transition" data-index="{{ $index }}">
                                                    <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                <span class="w-8 lg:w-10 text-center text-sm lg:text-base font-bold text-white">{{ $item['jumlah'] }}</span>
                                                <button class="btn-increase-qty w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center rounded-md bg-green-500 text-white hover:bg-green-600 transition" data-index="{{ $index }}">
                                                    <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            {{-- Delete Button --}}
                                            <button type="button" class="btn-remove-item w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center rounded-md bg-red-500/80 text-white hover:bg-red-600 transition" data-index="{{ $index }}">
                                                <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex flex-col items-center justify-center h-full py-12 px-4">
                            <svg class="w-24 h-24 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-slate-400 font-semibold text-lg">Keranjang Kosong</p>
                            <p class="text-slate-500 text-sm mt-1">Tambahkan produk dari daftar</p>
                        </div>
                    @endif
                </div>

                {{-- Checkout Section - Sticky Bottom --}}
                @if(count(session('cart', [])) > 0)
                <div class="border-t border-slate-600/50 p-4 bg-slate-800 flex-shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.3)]">
                    {{-- Summary --}}
                    <div class="space-y-1.5 mb-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-300">Subtotal:</span>
                            <span class="font-semibold text-white">Rp {{ isset($total) ? number_format($total, 0, ',', '.') : '0' }}</span>
                        </div>
                        <div class="border-t border-slate-600/50 pt-2 flex justify-between items-center">
                            <span class="text-base font-bold text-white">TOTAL:</span>
                            <span class="text-xl font-bold text-green-400">Rp {{ isset($total) ? number_format($total, 0, ',', '.') : '0' }}</span>
                        </div>
                    </div>

                    {{-- Checkout Button - ALWAYS VISIBLE --}}
                    <button type="button" onclick="openPaymentModal()" id="checkout-btn"
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition font-bold text-sm shadow-xl flex items-center justify-center gap-2"
                        style="display: flex !important; visibility: visible !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Proses Pembayaran
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

{{-- Modal Konfirmasi Pembayaran --}}
<x-modal name="payment-confirmation-modal" :show="false">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Konfirmasi Pembayaran
            </h3>
            <button @click="window.location.href='{{ route('transaksi.index') }}'"
                class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('transaksi.confirmOrder') }}" id="payment-form"
            onsubmit="handlePaymentSubmit(event)">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer->id ?? '' }}" />

            <!-- Total Belanja & Kembalian Section -->
            <div class="grid grid-cols-2 gap-3 mb-6">
                <!-- Total Belanja -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-xs font-medium opacity-90 mb-1">Total Belanja</div>
                    <div class="text-2xl font-bold" id="modal-total-belanja">Rp 0</div>
                </div>

                <!-- Kembalian -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-5 text-white shadow-lg">
                    <div class="text-xs font-medium opacity-90 mb-1">Kembalian</div>
                    <div class="text-2xl font-bold" id="modal-kembalian">Rp 0</div>
                </div>
            </div>

            <!-- Nama Customer -->
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Nama Customer</span>
                    <span class="text-sm font-medium text-gray-900"
                        id="modal-nama-customer">{{ $customer->nama_customer ?? 'Umum' }}</span>
                </div>
            </div>

            <!-- Nominal Bayar -->
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Nominal Bayar</span>
                    <span class="text-sm font-medium text-gray-900" id="modal-nominal-bayar">Rp 0</span>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-2">Metode Pembayaran</label>
                <select name="tipe_pembayaran" id="modal-tipe_pembayaran"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white text-gray-700"
                    required onchange="handlePaymentMethodChange()">
                    <option value="">Pilih Metode</option>
                    <option value="cash">💵 Cash</option>
                    <option value="transfer">🏦 Transfer Bank</option>
                </select>
            </div>

            <!-- Input Jumlah Bayar Manual -->
            <div class="mb-6" id="uang_dibayar_container">
                <label for="uang_dibayar_input" class="block text-sm text-gray-600 mb-2">Masukkan Jumlah Bayar</label>
                <input type="number" id="uang_dibayar_input" min="0" step="1000"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white text-gray-700"
                    placeholder="Masukkan jumlah bayar..." oninput="calculateChange()" />
            </div>

            <!-- Hidden Input for Amount -->
            <input type="hidden" id="modal-uang_dibayar" name="uang_dibayar" value="0" />

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="button" @click="$dispatch('close-modal', 'payment-confirmation-modal')"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition font-bold shadow-lg">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition font-bold shadow-lg">
                    Bayar
                </button>
            </div>
        </form>
    </div>
</x-modal>
@endsection

@push('scripts')
<script>
    // Variabel total dari server
    const cartTotal = {{ count(session('cart', [])) > 0 ? array_sum(array_map(function($item) { return $item['harga'] * $item['jumlah']; }, session('cart', []))) : 0 }};

    // Function to open payment modal
    function openPaymentModal() {
        updateModalTotal();
        document.dispatchEvent(new CustomEvent('open-modal', { detail: 'payment-confirmation-modal' }));
    }

    // Function to update modal with cart total
    function updateModalTotal() {
        document.getElementById('modal-total-belanja').textContent = 'Rp ' + cartTotal.toLocaleString('id-ID');
        document.getElementById('modal-nominal-bayar').textContent = 'Rp ' + cartTotal.toLocaleString('id-ID');
        document.getElementById('uang_dibayar_input').value = '';
        document.getElementById('modal-uang_dibayar').value = 0;
        calculateChange();
    }

    // Calculate change
    function calculateChange() {
        const total = cartTotal;
        const paid = parseInt(document.getElementById('uang_dibayar_input').value) || 0;
        const change = paid - total;

        document.getElementById('modal-uang_dibayar').value = paid;
        document.getElementById('modal-nominal-bayar').textContent = 'Rp ' + paid.toLocaleString('id-ID');
        document.getElementById('modal-kembalian').textContent = 'Rp ' + (change > 0 ? change.toLocaleString('id-ID') : '0');
    }

    // Filter category function
    function filterCategory(category) {
        document.getElementById('kategori-input').value = category;
        document.getElementById('filter-form').submit();
    }

    // Auto-submit search form on input (debounced) - Real-time search
    let searchTimeout;
    const searchBarangInput = document.querySelector('input[name="search_barang"]');
    if (searchBarangInput) {
        searchBarangInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500); // Wait 500ms after user stops typing
        });
    }

    // Handle payment method change
    function handlePaymentMethodChange() {
        const paymentMethod = document.getElementById('modal-tipe_pembayaran').value;
        const amountContainer = document.getElementById('uang_dibayar_container');
        const amountInput = document.getElementById('uang_dibayar_input');

        if (paymentMethod === 'transfer') {
            amountContainer.style.display = 'none';
            amountInput.value = '';
            amountInput.required = false;
            document.getElementById('modal-uang_dibayar').value = cartTotal;
            document.getElementById('modal-nominal-bayar').textContent = 'Rp ' + cartTotal.toLocaleString('id-ID');
            document.getElementById('modal-kembalian').textContent = 'Rp 0';
        } else {
            amountContainer.style.display = 'block';
            amountInput.required = true;
            calculateChange();
        }
    }

    // Handle payment form submit
    function handlePaymentSubmit(event) {
        const paymentMethod = document.getElementById('modal-tipe_pembayaran').value;
        const paid = parseInt(document.getElementById('modal-uang_dibayar').value);

        if (paymentMethod === 'cash' && paid < cartTotal) {
            event.preventDefault();
            alert('Jumlah bayar kurang dari total belanja!');
            return false;
        }
        return true;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateModalTotal();

        // Add input event listener
        const amountInput = document.getElementById('uang_dibayar_input');
        if (amountInput) {
            amountInput.addEventListener('input', calculateChange);
        }
    });
</script>
@endpush
