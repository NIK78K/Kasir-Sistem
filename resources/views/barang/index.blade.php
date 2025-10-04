@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    {{-- Judul --}}
    <h1 class="text-4xl font-extrabold text-center mb-8 text-gray-900">📦 Data Barang</h1>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-400 shadow">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Filter dan Search --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:space-x-6 space-y-4 md:space-y-0">
        <form method="GET" action="{{ route('barang.index') }}" class="flex flex-col md:flex-row md:items-center md:space-x-4 w-full max-w-3xl mx-auto">
            <select name="kategori" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition w-full md:w-48">
                <option value="">Kategori Spare Part</option>
                <option value="Elektronik" {{ request('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="Pakaian" {{ request('kategori') == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                <option value="Makanan" {{ request('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <input type="text" name="search" placeholder="Cari Barang" value="{{ request('search') }}" 
                class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 transition" />
            <button type="submit" 
                class="mt-3 md:mt-0 px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-semibold">
                Cari Barang
            </button>
        </form>
    </div>

    {{-- Hasil Pencarian --}}
    @if(request('search') || request('kategori'))
        <div class="mb-8 p-6 bg-gray-50 rounded-lg shadow-inner border border-gray-200 max-w-3xl mx-auto">
            <h2 class="font-semibold text-lg mb-4 text-gray-700">Hasil Pencarian</h2>
            @if($barangs->count() > 0)
                @foreach($barangs as $barang)
                    <div class="mb-3 p-3 bg-white rounded shadow-sm border border-gray-200">
                        {{ $barang->nama_barang }}
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">Tidak ada barang ditemukan.</p>
            @endif
        </div>
    @endif

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
                    <div class="mt-4 flex space-x-3">
                        <a href="{{ route('barang.edit', $barang->id) }}" 
                           class="flex-grow text-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                            Edit
                        </a>
                        <form action="{{ route('barang.destroy', $barang->id) }}" 
                              method="POST" class="flex-grow"
                              onsubmit="return confirm('Yakin hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">
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
