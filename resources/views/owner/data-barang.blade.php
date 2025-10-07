@extends('layouts.app')

@section('title', 'Data Barang (Owner)')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    {{-- Banner Selamat Datang --}}
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 p-6 shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            📦 Data Barang
        </h1>
    </div>

    {{-- Daftar Barang --}}
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
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
