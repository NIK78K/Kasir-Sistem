@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        {{-- Banner Selamat Datang --}}
        <div class="mb-8 rounded-2xl p-6 shadow-lg" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Data Customer
            </h1>
        </div>

        {{-- Tombol Tambah --}}
        <div class="mb-6 flex justify-end">
            <a href="{{ route('customer.create') }}"
                class="px-5 py-2.5 bg-gray-600 text-white rounded-lg shadow-md hover:bg-gray-700 transition flex items-center gap-2 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Customer
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg shadow" id="success-alert">
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
                        <th class="py-4 px-4 text-center font-semibold text-gray-800">Aksi</th>
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
                            <td class="py-4 px-4 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('customer.edit', $customer->id) }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition font-semibold text-xs">
                                        Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <button type="button"
                                            onclick="confirmDeleteCustomer({{ $customer->id }}, '{{ $customer->nama_customer }}')"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-xs">
                                        Hapus
                                    </button>
                                    <form id="delete-form-{{ $customer->id }}" action="{{ route('customer.destroy', $customer->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    {{-- Jika kosong --}}
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
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
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('customer.edit', $customer->id) }}"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition font-semibold text-center text-sm">
                            Edit
                        </a>
                        <button type="button"
                                onclick="confirmDeleteCustomer({{ $customer->id }}, '{{ $customer->nama_customer }}')"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-sm">
                            Hapus
                        </button>
                        <form id="delete-form-mobile-{{ $customer->id }}" action="{{ route('customer.destroy', $customer->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });

        function confirmDeleteCustomer(id, namaCustomer) {
            Swal.fire({
                title: 'Apakah Anda yakin menghapus customer?',
                text: `Customer "${namaCustomer}" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
