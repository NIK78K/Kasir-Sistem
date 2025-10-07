@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Edit Barang</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_barang" class="block font-semibold mb-1">Nama Barang:</label>
                <input id="nama_barang" type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="harga" class="block font-semibold mb-1">Harga:</label>
                <input id="harga" type="number" name="harga" value="{{ old('harga', $barang->harga) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="harga_grosir" class="block font-semibold mb-1">Harga Grosir:</label>
                <input id="harga_grosir" type="number" name="harga_grosir" value="{{ old('harga_grosir', $barang->harga_grosir) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="diskon" class="block font-semibold mb-1">Diskon (%):</label>
                <input id="diskon" type="number" name="diskon" value="{{ old('diskon', $barang->diskon) }}" min="0" max="100"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="stok" class="block font-semibold mb-1">Stok:</label>
                <input id="stok" type="number" name="stok" value="{{ old('stok', $barang->stok) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="kategori" class="block font-semibold mb-1">Kategori:</label>
                <select id="kategori" name="kategori"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Elektronik" {{ old('kategori', $barang->kategori) == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Pakaian" {{ old('kategori', $barang->kategori) == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                    <option value="Makanan" {{ old('kategori', $barang->kategori) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="Lainnya" {{ old('kategori', $barang->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div>
                <label for="gambar" class="block font-semibold mb-1">Gambar:</label>
                <input id="gambar" type="file" name="gambar" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('barang.index') }}" class="text-blue-600 hover:underline">Kembali</a>
        </div>
    </div>
@endsection
