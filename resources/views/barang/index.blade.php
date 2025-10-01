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

    {{-- Tombol Tambah --}}
    <div class="flex justify-end mb-4">
        <a href="{{ route('barang.create') }}" 
           class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
            Tambah Barang
        </a>
    </div>

    {{-- Card Table --}}
    <div class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-4">
            <h3 class="text-lg font-semibold text-white">📋 Daftar Barang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-3 border text-left">ID</th>
                        <th class="px-4 py-3 border text-left">Nama Barang</th>
                        <th class="px-4 py-3 border text-left">Harga</th>
                        <th class="px-4 py-3 border text-left">Stok</th>
                        <th class="px-4 py-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($barangs as $barang)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 border">{{ $barang->id }}</td>
                            <td class="px-4 py-3 border font-medium text-gray-800">{{ $barang->nama_barang }}</td>
                            <td class="px-4 py-3 border">Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 border text-center">{{ $barang->stok }}</td>
                            <td class="px-4 py-3 border text-center space-x-2">
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
