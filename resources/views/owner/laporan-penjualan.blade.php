@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    {{-- Header --}}
    <div class="mb-8 rounded-2xl p-6 shadow-lg bg-gray-700">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Laporan Penjualan
        </h1>
    </div>
    <h2 class="sr-only">Filter dan Pencarian</h2>

    {{-- Filter dan Search --}}
    <div class="mb-8">
        <form method="GET" action="{{ route('owner.laporanPenjualan') }}" id="filter-form" class="space-y-3">
            {{-- Search Bar dan Export Button --}}
            <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari berdasarkan No. Transaksi, Customer, atau Barang" value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <a href="{{ route('owner.laporanPenjualanExport') }}"
                   class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-semibold flex items-center justify-center gap-2 shadow-lg whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>
            </div>

            {{-- Filter Pills dan Date Range --}}
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                {{-- Filter Pills (Left Side) --}}
                <div class="flex flex-wrap gap-2 items-center">
                    {{-- Payment Type Filter --}}
                    @if(isset($allPaymentTypes))
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-gray-600">Metode:</span>
                            <div class="flex gap-2 flex-wrap">
                                <button type="button" data-payment="" class="payment-filter-btn px-3 py-1.5 rounded-full text-xs font-semibold transition {{ !request('tipe_pembayaran') ? 'bg-blue-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Semua
                                </button>
                                @foreach($allPaymentTypes as $payment)
                                    <button type="button" data-payment="{{ $payment }}" class="payment-filter-btn px-3 py-1.5 rounded-full text-xs font-semibold transition {{ request('tipe_pembayaran') == $payment ? 'bg-blue-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        {{ ucfirst($payment) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Date Range Filter (Right Side on Desktop) --}}
                <div class="flex flex-col sm:flex-row gap-3 md:ml-auto">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-gray-600 whitespace-nowrap">Dari:</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="flex-1 sm:w-auto px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="document.getElementById('filter-form').submit()">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-gray-600 whitespace-nowrap">Sampai:</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="flex-1 sm:w-auto px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="document.getElementById('filter-form').submit()">
                    </div>
                </div>
            </div>

            <input type="hidden" name="tipe_pembayaran" id="tipe_pembayaran_input" value="{{ request('tipe_pembayaran') }}">
        </form>
    </div>

    {{-- Table for Desktop --}}
    <div class="hidden md:block overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-300">
                <tr>
                    <th class="py-4 px-4 text-center font-semibold text-gray-800">No</th>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Nomor Transaksi</th>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Tanggal</th>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Nama Customer</th>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Tipe Customer</th>
                    <th class="py-4 px-4 text-left font-semibold text-gray-800">Barang</th>
                    <th class="py-4 px-4 text-center font-semibold text-gray-800">Jumlah Barang</th>
                    <th class="py-4 px-4 text-center font-semibold text-gray-800">Total Harga</th>
                    <th class="py-4 px-4 text-center font-semibold text-gray-800">Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($orders as $index => $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-4 text-center">{{ $orders->firstItem() + $index }}</td>
                    <td class="py-4 px-4">{{ $order->order_id }}</td>
                    <td class="py-4 px-4">{{ \Carbon\Carbon::parse($order->tanggal_pembelian)->format('d/m/Y H:i') }}</td>
                    <td class="py-4 px-4">{{ $order->customer ? $order->customer->nama_customer : '-' }}</td>
                    <td class="py-4 px-4">{{ $order->customer ? ucfirst($order->customer->tipe_pembeli) : '-' }}</td>
                    <td class="py-4 px-4">
                        <div class="text-sm">
                            @if($order->items && $order->items->count() > 0)
                                @foreach($order->items as $index => $item)
                                    @if($index > 0), @endif
                                    <span class="font-medium">{{ $item->barang ? $item->barang->nama_barang : 'Barang tidak ditemukan' }}</span>
                                    <span class="text-gray-500">({{ $item->jumlah }})</span>
                                @endforeach
                            @else
                                <span class="text-gray-500">Tidak ada item</span>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 px-4 text-center">{{ $order->total_jumlah }}</td>
                    <td class="py-4 px-4 text-center text-sm font-semibold text-blue-600">
                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-4 text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $order->tipe_pembayaran === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($order->tipe_pembayaran) }}
                        </span>
                    </td>
                </tr>
                @endforeach

                {{-- Jika kosong --}}
                @if ($orders->isEmpty())
                    <tr>
                        <td colspan="9" class="py-8 text-center text-gray-500">
                            @if(request('search') || request('tipe_pembayaran') || request('tanggal_dari') || request('tanggal_sampai'))
                                <p>Tidak ditemukan transaksi yang cocok dengan filter yang dipilih.</p>
                            @else
                                Belum ada data penjualan.
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Cards for Mobile --}}
    <div class="block md:hidden space-y-4">
        @foreach ($orders as $index => $order)
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-4">
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>No:</strong> {{ $orders->firstItem() + $index }}</p>
                    <p><strong>Nomor Transaksi:</strong> {{ $order->order_id }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($order->tanggal_pembelian)->format('d/m/Y H:i') }}</p>
                    <p><strong>Nama Customer:</strong> {{ $order->customer ? $order->customer->nama_customer : '-' }}</p>
                    <p><strong>Tipe Customer:</strong> {{ $order->customer ? ucfirst($order->customer->tipe_pembeli) : '-' }}</p>
                    <p><strong>Barang:</strong></p>
                    <div class="ml-4 text-xs">
                        @if($order->items && $order->items->count() > 0)
                            @foreach($order->items as $index => $item)
                                <p>• {{ $item->barang ? $item->barang->nama_barang : 'Barang tidak ditemukan' }} ({{ $item->jumlah }})</p>
                            @endforeach
                        @else
                            <p class="text-gray-500">Tidak ada item</p>
                        @endif
                    </div>
                    <p><strong>Jumlah Barang:</strong> {{ $order->total_jumlah }}</p>
                    <p class="text-blue-600 font-semibold"><strong>Total Harga:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                    <p>
                        <strong>Metode Pembayaran:</strong>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $order->tipe_pembayaran === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($order->tipe_pembayaran) }}
                        </span>
                    </p>
                </div>
            </div>
        @endforeach

        {{-- Jika kosong --}}
        @if ($orders->isEmpty())
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                @if(request('search') || request('tipe_pembayaran') || request('tanggal_dari') || request('tanggal_sampai'))
                    <p>Tidak ditemukan transaksi yang cocok dengan filter yang dipilih.</p>
                @else
                    Belum ada data penjualan.
                @endif
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->appends(request()->except('page'))->links() }}
        </div>
    @endif
</div>
@endsection
