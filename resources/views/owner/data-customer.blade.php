@extends('layouts.app')

@section('title', 'Data Customer (Owner)')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
    {{-- Banner Selamat Datang --}}
        <div class="mb-8 rounded-2xl p-6 shadow-lg bg-gray-700">
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Data Customer
            </h1>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg shadow">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Table for Desktop --}}
        <div class="hidden md:block overflow-x-auto bg-white shadow-lg rounded-xl border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">ID</th>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">Nama Customer</th>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">Alamat</th>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">No HP</th>
                        <th class="py-4 px-4 text-left font-semibold text-gray-800">Tipe Pembeli</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($customers as $customer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-4">{{ $customer->id }}</td>
                            <td class="py-4 px-4 font-medium text-gray-900">{{ $customer->nama_customer }}</td>
                            <td class="py-4 px-4">{{ $customer->alamat }}</td>
                            <td class="py-4 px-4">{{ $customer->no_hp }}</td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $customer->tipe_pembeli == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($customer->tipe_pembeli) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach

                    {{-- Jika kosong --}}
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
                                Belum ada data customer.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Cards for Mobile --}}
        <div class="block md:hidden space-y-4">
            @foreach ($customers as $customer)
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-bold text-gray-900">{{ $customer->nama_customer }}</h3>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $customer->tipe_pembeli == 'grosir' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($customer->tipe_pembeli) }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>ID:</strong> {{ $customer->id }}</p>
                        <p><strong>Alamat:</strong> {{ $customer->alamat }}</p>
                        <p><strong>No HP:</strong> {{ $customer->no_hp }}</p>
                    </div>
                </div>
            @endforeach

            {{-- Jika kosong --}}
            @if ($customers->isEmpty())
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                    Belum ada data customer.
                </div>
            @endif
        </div>
    </div>
@endsection
