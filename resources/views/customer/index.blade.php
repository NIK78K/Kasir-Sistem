@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Data Customer
            </h1>
            <a href="{{ route('customer.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Customer
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="py-3 px-4 text-left font-semibold">ID</th>
                        <th class="py-3 px-4 text-left font-semibold">Nama Customer</th>
                        <th class="py-3 px-4 text-left font-semibold">Alamat</th>
                        <th class="py-3 px-4 text-left font-semibold">No HP</th>
                        <th class="py-3 px-4 text-left font-semibold">Tipe Pembeli</th>
                        <th class="py-3 px-4 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-4">{{ $customer->id }}</td>
                            <td class="py-3 px-4 font-medium">{{ $customer->nama_customer }}</td>
                            <td class="py-3 px-4">{{ $customer->alamat }}</td>
                            <td class="py-3 px-4">{{ $customer->no_hp }}</td>
                            <td class="py-3 px-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                    {{ $customer->tipe_pembeli == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($customer->tipe_pembeli) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center space-x-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('customer.edit', $customer->id) }}"
                                    class="inline-block px-3 py-1.5 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">
                                    Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-block px-3 py-1.5 bg-red-600 text-white rounded-md shadow hover:bg-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    {{-- Jika kosong --}}
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">
                                Belum ada data customer.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
