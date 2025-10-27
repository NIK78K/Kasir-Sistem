@extends('layouts.app')

@section('title', auth()->user()->role == 'owner' ? 'Laporan Return Barang' : 'List Return Barang')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        {{-- Banner Selamat Datang --}}
        <div class="mb-8 rounded-2xl p-6 shadow-lg bg-gray-700">
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ auth()->user()->role == 'owner' ? '📋 Laporan Return Barang' : '🔄 List Return Barang' }}
            </h1>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg shadow" id="success-alert">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Table for Desktop --}}
        <div class="hidden md:block overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">Nomor Transaksi</th>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">Nama Customer</th>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">Tipe Pembeli</th>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">Nama Barang</th>
                        <th class="py-4 px-4 text-center font-semibold text-gray-800">Jumlah</th>
                        @if(auth()->user()->role == 'owner')
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Status</th>
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Tanggal Return</th>
                        @else
                            <th class="py-4 px-4 text-center font-semibold text-gray-800">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($transaksis as $transaksi)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-4">{{ str_pad($transaksi->id, 9, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-4 px-4 font-medium text-gray-900">{{ $transaksi->customer->nama_customer }}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ ($transaksi->customer->tipe_pembeli ?? '') == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($transaksi->customer->tipe_pembeli ?? '-') }}
                                </span>
                            </td>
                            <td class="py-4 px-4">{{ $transaksi->barang->nama_barang }}</td>
                            <td class="py-4 px-4 text-center">{{ $transaksi->jumlah }}</td>
                            @if(auth()->user()->role == 'owner')
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $transaksi->status == 'return' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">{{ $transaksi->updated_at->format('d-m-Y H:i') }}</td>
                            @else
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('transaksi.barangReturn', ['id' => $transaksi->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                        Return
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                    {{-- Jika kosong --}}
                    @if ($transaksis->isEmpty())
                        <tr>
                            <td colspan="{{ auth()->user()->role == 'owner' ? '7' : '6' }}" class="py-8 text-center text-gray-500">
                                {{ auth()->user()->role == 'owner' ? 'Belum ada data return barang.' : 'Tidak ada transaksi yang dapat direturn.' }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Cards for Mobile --}}
        <div class="block md:hidden space-y-4">
            @foreach ($transaksis as $transaksi)
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-bold text-gray-900">{{ $transaksi->customer->nama_customer }}</h3>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ ($transaksi->customer->tipe_pembeli ?? '') == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($transaksi->customer->tipe_pembeli ?? '-') }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Nomor Transaksi:</strong> {{ str_pad($transaksi->id, 9, '0', STR_PAD_LEFT) }}</p>
                        <p><strong>Nama Barang:</strong> {{ $transaksi->barang->nama_barang }}</p>
                        <p><strong>Jumlah:</strong> {{ $transaksi->jumlah }}</p>
                        @if(auth()->user()->role == 'owner')
                            <p><strong>Status:</strong>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $transaksi->status == 'return' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
                                </span>
                            </p>
                            <p><strong>Tanggal Return:</strong> {{ $transaksi->updated_at->format('d-m-Y H:i') }}</p>
                        @else
                            <div class="mt-4">
                                <a href="{{ route('transaksi.barangReturn', ['id' => $transaksi->id]) }}"
                                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 transition font-semibold text-center text-sm">
                                    Return
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Jika kosong --}}
            @if ($transaksis->isEmpty())
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                    {{ auth()->user()->role == 'owner' ? 'Belum ada data return barang.' : 'Tidak ada transaksi yang dapat direturn.' }}
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        @if($transaksis->hasPages())
            <div class="mt-6">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endsection
