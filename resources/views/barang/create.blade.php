@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Barang</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.store') }}" method="POST" class="max-w-md">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2" for="nama_barang">Nama Barang:</label>
            <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2" for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" value="{{ old('harga') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2" for="stok">Stok Barang:</label>
            <input type="number" name="stok" id="stok" min="0" value="{{ old('stok', $barang->stok ?? 0) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <button type="submit" 
            class="bg-blue-600 text-white font-semibold px-6 py-2 rounded hover:bg-blue-700 transition duration-200">
            Simpan
        </button>
    </form>

    <a href="{{ route('barang.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">Kembali</a>
@endsection
