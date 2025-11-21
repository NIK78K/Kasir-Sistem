@extends('layouts.app')

@section('title', auth()->user()->role == 'owner' ? 'Laporan Return Barang' : 'List Return Barang')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    {{-- Header --}}
    <div class="mb-8 rounded-2xl p-6 shadow-lg bg-gray-700">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            @if(auth()->user()->role == 'owner')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Return Barang
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Riwayat Transaksi dan Return Barang
            @endif
        </h1>
    </div>
    <h2 class="sr-only">Filter dan Pencarian</h2>

    {{-- Alert (Hidden - using global popup) --}}
    @if (session('success'))
        <div class="hidden" id="success-alert">{{ session('success') }}</div>
    @endif

        {{-- Filter dan Search --}}
        <div class="mb-8">
            <form method="GET" action="{{ auth()->user()->role == 'owner' ? route('owner.laporanBarangReturn') : route('transaksi.listReturnable') }}" id="filter-form" class="space-y-3">
                {{-- Search Bar --}}
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari berdasarkan No. Transaksi, Customer atau Barang," value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
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

                        {{-- Status Filter (Only for Owner) --}}
                        @if(auth()->user()->role == 'owner' && isset($allStatus))
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-semibold text-gray-600">Status:</span>
                                <div class="flex gap-2 flex-wrap">
                                    <button type="button" data-status="" class="status-filter-btn px-3 py-1.5 rounded-full text-xs font-semibold transition {{ !request('status') ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        Semua
                                    </button>
                                    @foreach($allStatus as $statusItem)
                                        <button type="button" data-status="{{ $statusItem }}" class="status-filter-btn px-3 py-1.5 rounded-full text-xs font-semibold transition {{ request('status') == $statusItem ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                            {{ ucfirst(str_replace('_', ' ', $statusItem)) }}
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
                <input type="hidden" name="status" id="status_input" value="{{ request('status') }}">
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
                        @if(auth()->user()->role == 'owner')
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Status</th>
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Tanggal Return</th>
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Alasan Return</th>
                        @else
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($orders as $index => $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-4 text-center">{{ $orders->firstItem() + $index }}</td>
                            <td class="py-4 px-4">{{ $order->order_id }}</td>
                            <td class="py-4 px-4">{{ $order->tanggal_pembelian->format('d-m-Y') }}</td>
                            <td class="py-4 px-4">{{ $order->customer ? $order->customer->nama_customer : '-' }}</td>
                            <td class="py-4 px-4">{{ $order->customer ? ucfirst($order->customer->tipe_pembeli) : '-' }}</td>
                            <td class="py-4 px-4">
                                <div class="text-sm">
                                    @foreach($order->items as $index => $item)
                                        @if($index > 0), @endif
                                        @if($item->barang)
                                            <span class="font-medium">{{ $item->barang->nama_barang }}</span>
                                            <span class="text-gray-500">({{ $item->jumlah }})</span>
                                        @else
                                            <span class="font-medium text-red-500">Barang tidak ditemukan</span>
                                            <span class="text-gray-500">({{ $item->jumlah }})</span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center">{{ $order->total_jumlah }}</td>
                            <td class="py-4 px-4 text-right text-sm font-semibold text-blue-600">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $order->tipe_pembayaran === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($order->tipe_pembayaran) }}
                                </span>
                            </td>
                            @if(auth()->user()->role == 'owner')
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $order->status == 'return' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">{{ $order->updated_at->format('d-m-Y H:i') }}</td>
                                <td class="py-4 px-4 text-center">{{ $order->alasan_return ?: '-' }}</td>
                            @else
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('transaksi.barangReturn', ['id' => $order->items->first()->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        Return
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                    {{-- Jika kosong --}}
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="{{ auth()->user()->role == 'owner' ? '12' : '9' }}" class="py-8 text-center text-gray-500">
                                @if(request('search') || request('tipe_pembayaran') || request('status') || request('tanggal_dari') || request('tanggal_sampai'))
                                    <p>Tidak ditemukan transaksi yang cocok dengan filter yang dipilih.</p>
                                @else
                                    {{ auth()->user()->role == 'owner' ? 'Belum ada data return barang.' : 'Tidak ada transaksi yang dapat direturn.' }}
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
                        <p><strong>Tanggal:</strong> {{ $order->tanggal_pembelian->format('d-m-Y') }}</p>
                        <p><strong>Nama Customer:</strong> {{ $order->customer ? $order->customer->nama_customer : '-' }}</p>
                        <p><strong>Tipe Customer:</strong> {{ $order->customer ? ucfirst($order->customer->tipe_pembeli) : '-' }}</p>
                        <p><strong>Barang:</strong></p>
                        <div class="ml-4 text-xs">
                            @foreach($order->items as $index => $item)
                                <p>• @if($item->barang)
                                    {{ $item->barang->nama_barang }} ({{ $item->jumlah }})
                                @else
                                    <span class="text-red-500">Barang tidak ditemukan</span> ({{ $item->jumlah }})
                                @endif
                                </p>
                            @endforeach
                        </div>
                        <p><strong>Jumlah Barang:</strong> {{ $order->total_jumlah }}</p>
                        <p class="text-blue-600 font-semibold"><strong>Total Harga:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                        <p>
                            <strong>Metode Pembayaran:</strong>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $order->tipe_pembayaran === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($order->tipe_pembayaran) }}
                            </span>
                        </p>
                        @if(auth()->user()->role == 'owner')
                            <p>
                                <strong>Status:</strong>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $order->status == 'return' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </p>
                            <p><strong>Tanggal Return:</strong> {{ $order->updated_at->format('d-m-Y H:i') }}</p>
                            @if($order->alasan_return)
                                <p><strong>Alasan Return:</strong> {{ $order->alasan_return }}</p>
                            @endif
                        @else
                            <div class="mt-4">
                                <a href="{{ route('transaksi.barangReturn', ['id' => $order->items->first()->id]) }}"
                                    class="block w-full px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 transition font-semibold text-center text-sm">
                                    Return
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Jika kosong --}}
            @if ($orders->isEmpty())
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                    @if(request('search') || request('tipe_pembayaran') || request('status') || request('tanggal_dari') || request('tanggal_sampai'))
                        <p>Tidak ditemukan transaksi yang cocok dengan filter yang dipilih.</p>
                    @else
                        {{ auth()->user()->role == 'owner' ? 'Belum ada data return barang.' : 'Tidak ada transaksi yang dapat direturn.' }}
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

    <!-- Modal for Image Preview -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
        <div class="relative max-w-4xl max-h-full p-4">
            <img id="modalImage" src="" alt="Bukti Return" class="max-w-full max-h-full object-contain">
            <button onclick="closeImageModal()" class="absolute top-2 right-2 text-white bg-red-600 hover:bg-red-700 rounded-full w-8 h-8 flex items-center justify-center text-xl font-bold">&times;</button>
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            // ensure flex display for centering
            modal.classList.add('flex');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
@endsection
