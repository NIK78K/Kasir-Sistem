@extends('layouts.app')

@section('title', 'Data Barang (Owner)')    

@section('content')
<div class="max-w-7xl mx-auto p-6">
        {{-- Banner Selamat Datang --}}
    <div class="mb-8 rounded-2xl p-6 shadow-lg bg-gray-700">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            📦 Data Barang
        </h1>
    </div>

    {{-- Filter dan Search --}}
    <div class="mb-8">
        <form method="GET" action="{{ route('owner.dataBarang') }}" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
            <select name="kategori" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 transition bg-white">
                <option value="">Kategori Spare Part</option>
                    <option value="Sepeda Pacifik" {{ request('kategori') == 'Sepeda Pacifik' ? 'selected' : '' }}>Sepeda Pacifik</option>
                    <option value="Sepeda Listrik" {{ request('kategori') == 'Sepeda Listrik' ? 'selected' : '' }}>Sepeda Listrik</option>
                    <option value="Ban" {{ request('kategori') == 'Ban' ? 'selected' : '' }}>Ban</option>
                    <option value="Sepeda Stroller" {{ request('kategori') == 'Sepeda Stroller' ? 'selected' : '' }}>Sepeda Stroller</option>
                    <option value="Sparepart" {{ request('kategori') == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
                    <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <input type="text" name="search" placeholder="Cari Barang" value="{{ request('search') }}"
                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 transition" />
            <button type="submit"
                class="px-6 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold whitespace-nowrap">
                Cari Barang
            </button>
        </form>
    </div>

    {{-- Hasil Pencarian --}}
    @if(request('search') || request('kategori'))
        <div class="mb-8 p-5 bg-gray-50 rounded-lg shadow border border-gray-200">
            <h2 class="font-semibold text-lg mb-3 text-gray-700">Hasil Pencarian</h2>
            @if($barangs->count() > 0)
                <div class="space-y-2">
                    @foreach($barangs as $barang)
                        <div class="p-3 bg-white rounded-lg shadow-sm border border-gray-200 hover:border-blue-400 transition">
                            {{ $barang->nama_barang }}
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tidak ada barang ditemukan.</p>
            @endif
        </div>
    @endif

    {{-- Daftar Barang --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($barangs as $barang)
            <div class="border rounded-xl p-5 flex flex-col space-y-4 shadow-md hover:shadow-2xl transition-all duration-300 bg-white hover:-translate-y-2 hover:scale-105">
                <div class="w-full h-40 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center relative">
                    @if($barang->gambar)
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="object-cover w-full h-full" />
                    @else
                        <span class="text-gray-400 italic">No Image</span>
                    @endif
                    @if($barang->isNew())
                        <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                            NEW
                        </div>
                    @endif
                </div>
                <div class="flex-grow flex flex-col justify-between">
                    <div class="space-y-2">
                        <p class="text-base font-bold text-gray-900 line-clamp-2">{{ $barang->nama_barang }}</p>
                        @if($barang->harga_grosir)
                            <p class="text-sm text-gray-600"><span class="font-semibold">Harga Grosir & Bengkel:</span>
                                <span class="text-blue-600">Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</span>
                            </p>
                        @else
                            <p class="text-sm text-gray-400"><span class="font-semibold">Harga Grosir & Bengkel:</span> -</p>
                        @endif
                        @if($barang->harga)
                            <p class="text-sm text-gray-600"><span class="font-semibold">Harga:</span>
                                <span class="text-green-600">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                            </p>
                        @else
                            <p class="text-sm text-gray-400"><span class="font-semibold">Harga:</span> -</p>
                        @endif
                        <p class="text-sm text-gray-600"><span class="font-semibold">Stok:</span> <span class="text-green-600 font-semibold">{{ $barang->stok }}</span></p>
                        <p class="text-sm text-gray-600"><span class="font-semibold">Kategori:</span> {{ $barang->kategori }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
