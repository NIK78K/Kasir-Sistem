@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Tambah Barang</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('barang.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="nama_barang" class="block font-semibold mb-1">Nama Barang:</label>
                <input id="nama_barang" type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nama barang" />
            </div>

            <div>
                <label for="harga" class="block font-semibold mb-1">Harga: <span class="text-sm text-gray-500">(isi salah satu: harga atau harga grosir)</span></label>
                <input id="harga" type="number" name="harga" value="{{ old('harga') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan harga" />
            </div>

            <div>
                <label for="harga_grosir" class="block font-semibold mb-1">Harga Grosir: <span class="text-sm text-gray-500">(isi salah satu: harga atau harga grosir)</span></label>
                <input id="harga_grosir" type="number" name="harga_grosir" value="{{ old('harga_grosir') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan harga grosir" />
            </div>



            <div>
                <label for="stok" class="block font-semibold mb-1">Stok:</label>
                <input id="stok" type="number" name="stok" value="{{ old('stok') }}" min="0"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan stok barang" />
            </div>

            <div>
                <label for="kategori" class="block font-semibold mb-1">Kategori:</label>
                <select id="kategori" name="kategori"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Sepeda Pacifik" {{ request('kategori') == 'Sepeda Pacifik' ? 'selected' : '' }}>Sepeda Pacifik</option>
                <option value="Sepeda Listrik" {{ request('kategori') == 'Sepeda Listrik' ? 'selected' : '' }}>Sepeda Listrik</option>
                <option value="Ban" {{ request('kategori') == 'Ban' ? 'selected' : '' }}>Ban</option>
                <option value="Sepeda Stroller" {{ request('kategori') == 'Sepeda Stroller' ? 'selected' : '' }}>Sepeda Stroller</option>
                <option value="Sparepart" {{ request('kategori') == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
                <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div>
                <label for="gambar" class="block font-semibold mb-1">Unggah Gambar Barang:</label>
                <input id="gambar" type="file" name="gambar" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('barang.index') }}" class="text-blue-600 hover:underline">Kembali</a>
        </div>
    </div>
@endsection
