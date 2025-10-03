@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    {{-- Judul --}}
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">📦 Data Barang</h1>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-300">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Filter dan Search --}}
    <div class="mb-6 flex items-center space-x-4">
        <form method="GET" action="{{ route('barang.index') }}" class="flex items-center space-x-2 w-full max-w-xl">
            <select name="kategori" class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Kategori Spare Part</option>
                <option value="Elektronik" {{ request('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                <option value="Pakaian" {{ request('kategori') == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                <option value="Makanan" {{ request('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <input type="text" name="search" placeholder="Cari Barang" value="{{ request('search') }}" 
                class="flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Cari Barang</button>
        </form>
    </div>

    {{-- Hasil Pencarian --}}
    @if(request('search') || request('kategori'))
        <div class="mb-6 p-4 bg-gray-100 rounded">
            <h2 class="font-semibold mb-2">Hasil Pencarian</h2>
            @if($barangs->count() > 0)
                @foreach($barangs as $barang)
                    <div class="mb-2 p-2 bg-white rounded shadow">
                        {{ $barang->nama_barang }}
                    </div>
                @endforeach
            @else
                <p>Tidak ada barang ditemukan.</p>
            @endif
        </div>
    @endif

    {{-- Daftar Barang --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($barangs as $barang)
            <div class="border rounded-lg p-4 flex space-x-4 items-center">
                <div class="w-32 h-32 bg-gray-200 rounded overflow-hidden flex items-center justify-center">
                    @if($barang->gambar)
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="object-cover w-full h-full" />
                    @else
                        <span class="text-gray-500">No Image</span>
                    @endif
                </div>
                <div class="flex-grow">
                    <p><strong>Nama Barang</strong><br>{{ $barang->nama_barang }}</p>
                    <p><strong>Harga Grosir & Bengkel</strong><br>Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</p>
                    <p><strong>Harga</strong><br>Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                    <p><strong>Kategori</strong><br>{{ $barang->kategori }}</p>
                    <div class="mt-4 space-x-2">
                        <a href="{{ route('barang.edit', $barang->id) }}" 
                           class="inline-block px-3 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('barang.destroy', $barang->id) }}" 
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Yakin hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 transition">
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
