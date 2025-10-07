@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Tambah Barang</h1>

        {{-- Error Validation --}}
        @if ($errors->any())
            <div class="mb-4 p-4 rounded bg-red-100 text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('barang.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf

            {{-- Nama Barang --}}
            <div>
                <label class="block font-medium text-gray-700">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan nama barang">
            </div>

            {{-- Harga --}}
            <div>
                <label class="block font-medium text-gray-700">Harga</label>
                <input type="number" name="harga" value="{{ old('harga') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan harga">
            </div>

            {{-- Harga Grosir --}}
            <div>
                <label class="block font-medium text-gray-700">Harga Grosir</label>
                <input type="number" name="harga_grosir" value="{{ old('harga_grosir') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan harga grosir">
            </div>

            {{-- Diskon --}}
            <div>
                <label class="block font-medium text-gray-700">Diskon (%)</label>
                <input type="number" name="diskon" value="{{ old('diskon', 0) }}" min="0" max="100"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan diskon dalam persen">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block font-medium text-gray-700">Kategori</label>
                <select name="kategori"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Pakaian" {{ old('kategori') == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                    <option value="Makanan" {{ old('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            {{-- Stok Barang --}}
            <div>
                <label class="block font-medium text-gray-700">Stok Barang</label>
                <input type="number" name="stok" value="{{ old('stok') }}" min="0"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan stok barang">
            </div>

            {{-- Unggah Gambar Barang --}}
            <div>
                <label class="block font-medium text-gray-700">Unggah Gambar Barang</label>
                <input type="file" name="gambar" accept="image/*"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
            </div>

            {{-- Tombol --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('barang.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 transition">
                    Kembali
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
