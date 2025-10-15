@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    {{-- Banner Selamat Datang --}}
    <div class="mb-8 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 p-6 shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            📦 Data Barang
        </h1>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-400 shadow">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Filter dan Search --}}
    <div class="mb-8">
        <form method="GET" action="{{ route('barang.index') }}" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
            <select name="kategori" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 transition bg-white">
                <option value="">Kategori Spare Part</option>
                <option value="Elektronik" {{ request('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="Pakaian" {{ request('kategori') == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                <option value="Makanan" {{ request('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <input type="text" name="search" placeholder="Cari Barang" value="{{ request('search') }}" 
                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 transition" />
            <button type="submit" 
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold whitespace-nowrap">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($barangs as $barang)
                    <div class="border rounded-xl p-4 sm:p-6 flex flex-col space-y-4 shadow-md hover:shadow-2xl transition-all duration-300 bg-white hover:bg-indigo-600 hover:text-white hover:-translate-y-2 hover:scale-105 group">
                <div class="w-full h-48 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center group-hover:bg-indigo-700 relative">
                    @if($barang->gambar)
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="object-cover w-full h-full group-hover:brightness-110" />
                    @else
                        <span class="text-gray-400 italic group-hover:text-white">No Image</span>
                    @endif
                    @if($barang->isNew())
                        <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                            NEW
                        </div>
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
                        @if($barang->harga_grosir)
                            <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Harga Grosir & Bengkel:</span>
                                @if($barang->diskon > 0)
                                    <span class="line-through">Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</span>
                                    <span class="ml-2 font-bold text-green-400">Rp {{ number_format($barang->harga_grosir * (100 - $barang->diskon) / 100, 0, ',', '.') }}</span>
                                @else
                                    Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-400 group-hover:text-gray-300"><span class="font-semibold">Harga Grosir & Bengkel:</span> -</p>
                        @endif
                        @if($barang->harga)
                            <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Harga:</span>
                                @if($barang->diskon > 0)
                                    <span class="line-through">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                                    <span class="ml-2 font-bold text-green-400">Rp {{ number_format($barang->harga * (100 - $barang->diskon) / 100, 0, ',', '.') }}</span>
                                @else
                                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-400 group-hover:text-gray-300"><span class="font-semibold">Harga:</span> -</p>
                        @endif
                        <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Stok:</span> <span class="text-green-600 font-semibold group-hover:text-green-200">{{ $barang->stok }}</span></p>
                        <p class="text-sm text-gray-600 group-hover:text-white"><span class="font-semibold">Kategori:</span> {{ $barang->kategori }}</p>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <a href="{{ route('barang.edit', $barang->id) }}" 
                           class="text-center px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition group-hover:bg-white group-hover:text-indigo-600">
                            Edit
                        </a>
                        <form action="{{ route('barang.destroy', $barang->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Yakin hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition group-hover:bg-white group-hover:text-red-600">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
