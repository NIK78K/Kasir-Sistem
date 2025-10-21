@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Edit Customer</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.update', $customer->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_customer" class="block font-semibold mb-1">Nama Customer:</label>
                <input id="nama_customer" type="text" name="nama_customer" value="{{ old('nama_customer', $customer->nama_customer) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nama customer" />
            </div>

            <div>
                <label for="alamat" class="block font-semibold mb-1">Alamat:</label>
                <textarea id="alamat" name="alamat"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan alamat">{{ old('alamat', $customer->alamat) }}</textarea>
            </div>

            <div>
                <label for="tipe_pembeli" class="block font-semibold mb-1">Tipe Pembeli:</label>
                <select id="tipe_pembeli" name="tipe_pembeli"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="pembeli" {{ old('tipe_pembeli', $customer->tipe_pembeli) == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                <option value="bengkel" {{ old('tipe_pembeli', $customer->tipe_pembeli) == 'bengkel' ? 'selected' : '' }}>Bengkel</option>
                <option value="langganan" {{ old('tipe_pembeli', $customer->tipe_pembeli) == 'langganan' ? 'selected' : '' }}>Langganan</option>
                </select>
            </div>

            <div>
                <label for="no_hp" class="block font-semibold mb-1">No HP:</label>
                <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp', $customer->no_hp) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nomor HP" />
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('customer.index') }}" class="text-blue-600 hover:underline">Kembali</a>
        </div>
    </div>
@endsection
