@extends('layouts.app')

@section('title', 'Data Barang (Owner)')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    {{-- Judul --}}
    <h1 class="text-4xl font-extrabold text-center mb-8 text-gray-900">📦 Data Barang (Hanya Lihat)</h1>

    {{-- Daftar Barang --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($barangs as $barang)
            <div class="border rounded-xl p-6 flex flex-col space-y-4 shadow hover:shadow-lg transition duration-300 bg-white">
                <div class="w-full h-40 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                    @if($barang->gambar)
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="object-contain w-full h-full" />
                    @else
                        <span class="text-gray-400 italic">No Image</span>
                    @endif
                </div>
                <div class="flex-grow flex flex-col justify-between">
                    <div class="space-y-2">
                        <p class="text-xl font-semibold text-gray-900">{{ $barang->nama_barang }}</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold">Harga Grosir & Bengkel:</span> Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold">Harga:</span> Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold">Stok:</span> <span class="text-green-600 font-semibold">{{ $barang->stok }}</span></p>
                        <p class="text-sm text-gray-600"><span class="font-semibold">Kategori:</span> {{ $barang->kategori }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
