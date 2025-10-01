@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Tambah Customer</h1>

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
        <form action="{{ route('customer.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Nama Customer --}}
            <div>
                <label class="block font-medium text-gray-700">Nama Customer</label>
                <input type="text" name="nama_customer" value="{{ old('nama_customer') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan nama customer">
            </div>

            {{-- Alamat --}}
            <div>
                <label class="block font-medium text-gray-700">Alamat</label>
                <textarea name="alamat"
                          class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                          placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>
            </div>

            {{-- Tipe Pembeli --}}
            <div>
                <label class="block font-medium text-gray-700">Tipe Pembeli</label>
                <select name="tipe_pembeli"
                        class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
                    <option value="pembeli" {{ old('tipe_pembeli') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                    <option value="bengkel" {{ old('tipe_pembeli') == 'bengkel' ? 'selected' : '' }}>Bengkel</option>
                    <option value="langganan" {{ old('tipe_pembeli') == 'langganan' ? 'selected' : '' }}>Langganan</option>
                </select>
            </div>

            {{-- No HP --}}
            <div>
                <label class="block font-medium text-gray-700">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none"
                       placeholder="Masukkan nomor HP">
            </div>

            {{-- Tombol --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('customer.index') }}"
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
