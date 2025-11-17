@extends('layouts.app')

@section('title', 'Data Customer')

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
        <h2 class="sr-only">Daftar Customer</h2>

        {{-- Filter dan Search --}}
        <div class="mb-8">
            <form method="GET" action="{{ route('customer.index') }}" id="filter-form" class="space-y-3">
                {{-- Search Bar --}}
                <div class="relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari Customer (Nama, Alamat, No HP)" value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                {{-- Tipe Pembeli Filter Pills --}}
                <div class="flex flex-wrap gap-2 items-center">
                    <button type="button" data-tipe="" class="tipe-filter-btn tipe-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ !request('tipe_pembeli') ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua Tipe
                    </button>
                    @foreach($allTipePembeli as $tipe)
                        <button type="button" data-tipe="{{ $tipe }}" class="tipe-filter-btn tipe-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ request('tipe_pembeli') == $tipe ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ ucfirst($tipe) }}
                        </button>
                    @endforeach
                    <div class="ml-auto">
                        <button type="button"
                            class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-semibold whitespace-nowrap flex items-center gap-2"
                            @click="$dispatch('open-modal', 'create-customer-modal')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Customer
                        </button>
                    </div>
                </div>

                <input type="hidden" name="tipe_pembeli" id="tipe_pembeli_input" value="{{ request('tipe_pembeli') }}">
            </form>
        </div>

        {{-- Alert (Hidden - using global popup) --}}
        @if (session('success'))
            <div class="hidden" id="success-alert">{{ session('success') }}</div>
        @endif

        @if (session('info'))
            <div class="hidden" id="info-alert">{{ session('info') }}</div>
        @endif

        @if (session('error'))
            <div class="hidden" id="error-alert">{{ session('error') }}</div>
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
                        <tr id="customer-{{ $customer->id }}" class="hover:bg-gray-50 transition">
                            <td class="py-4 px-4">{{ $customer->id }}</td>
                            <td class="py-4 px-4 font-medium text-gray-900">{{ $customer->nama_customer }}</td>
                            <td class="py-4 px-4">{{ $customer->alamat }}</td>
                            <td class="py-4 px-4">{{ $customer->no_hp }}</td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ in_array($customer->tipe_pembeli, ['bengkel_langganan', 'bengkel', 'langganan']) ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $customer->tipe_pembeli == 'bengkel_langganan' || $customer->tipe_pembeli == 'bengkel' || $customer->tipe_pembeli == 'langganan' ? 'Bengkel Langganan' : 'Pembeli' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button type="button"
                                        @click="$dispatch('open-modal', 'edit-customer-modal-{{ $customer->id }}')"
                                        class="btn-edit-customer px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition font-semibold text-xs"
                                        data-id="{{ $customer->id }}">
                                        Edit
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button type="button"
                                            class="btn-delete-customer px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-xs"
                                            data-id="{{ $customer->id }}"
                                            data-nama="{{ $customer->nama_customer }}">
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
                                @if(request('tipe_pembeli'))
                                    <p>Tidak ditemukan customer dengan tipe <span class="font-semibold">"{{ ucfirst(request('tipe_pembeli')) }}"</span>.</p>
                                @elseif(request('search'))
                                    <p>Tidak ditemukan customer dengan kata kunci <span class="font-semibold">"{{ request('search') }}"</span>.</p>
                                @else
                                    Belum ada data customer.
                                @endif
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
                        <p class="text-lg font-bold text-gray-900">{{ $customer->nama_customer }}</p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ in_array($customer->tipe_pembeli, ['bengkel_langganan', 'bengkel', 'langganan']) ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ $customer->tipe_pembeli == 'bengkel_langganan' || $customer->tipe_pembeli == 'bengkel' || $customer->tipe_pembeli == 'langganan' ? 'Bengkel Langganan' : 'Pembeli' }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>ID:</strong> {{ $customer->id }}</p>
                        <p><strong>Alamat:</strong> {{ $customer->alamat }}</p>
                        <p><strong>No HP:</strong> {{ $customer->no_hp }}</p>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button type="button"
                            @click="$dispatch('open-modal', 'edit-customer-modal-{{ $customer->id }}')"
                            class="btn-edit-customer flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition font-semibold text-center text-sm"
                            data-id="{{ $customer->id }}">
                            Edit
                        </button>
                        <button type="button"
                                class="btn-delete-customer w-full px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition font-semibold text-sm"
                                data-id="{{ $customer->id }}"
                                data-nama="{{ $customer->nama_customer }}">
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
                    @if(request('tipe_pembeli'))
                        <p>Tidak ditemukan customer dengan tipe <span class="font-semibold">"{{ ucfirst(request('tipe_pembeli')) }}"</span>.</p>
                    @elseif(request('search'))
                        <p>Tidak ditemukan customer dengan kata kunci <span class="font-semibold">"{{ request('search') }}"</span>.</p>
                    @else
                        Belum ada data customer.
                    @endif
                </div>
            @endif
        </div>
{{-- Modal Create Customer --}}
<x-modal name="create-customer-modal" maxWidth="2xl"
    x-on:close-modal.window="if ($event.detail === 'create-customer-modal') { $refs.createCustomerForm?.reset(); }"
    x-on:open-modal.window="if ($event.detail === 'create-customer-modal') { $refs.createCustomerForm?.reset(); }">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah Customer Baru</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form x-ref="createCustomerForm" id="create-customer-form" action="{{ route('customer.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nama_customer" class="block font-semibold mb-1">Nama Customer:</label>
                <input type="text" name="nama_customer" id="nama_customer"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nama customer" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="alamat" class="block font-semibold mb-1">Alamat:</label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan alamat"></textarea>
                </div>

                <div>
                    <label for="tipe_pembeli" class="block font-semibold mb-1">Tipe Pembeli:</label>
                    <select name="tipe_pembeli" id="tipe_pembeli"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pembeli">Pembeli</option>
                        <option value="bengkel_langganan">Bengkel Langganan</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="no_hp" class="block font-semibold mb-1">No HP:</label>
                <input type="text" name="no_hp" id="no_hp"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan no HP" onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                    @click="$refs.createCustomerForm.reset(); $dispatch('close-modal', 'create-customer-modal')"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-modal>

{{-- Modal Edit Customer --}}
@foreach ($customers as $customer)
<x-modal name="edit-customer-modal-{{ $customer->id }}" maxWidth="2xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Customer</h2>

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
                <label for="nama_customer_{{ $customer->id }}" class="block font-semibold mb-1">Nama Customer:</label>
                <input type="text" name="nama_customer" id="nama_customer_{{ $customer->id }}" value="{{ $customer->nama_customer }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nama customer" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="alamat_{{ $customer->id }}" class="block font-semibold mb-1">Alamat:</label>
                    <textarea name="alamat" id="alamat_{{ $customer->id }}" rows="3"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan alamat">{{ $customer->alamat }}</textarea>
                </div>

                <div>
                    <label for="tipe_pembeli_{{ $customer->id }}" class="block font-semibold mb-1">Tipe Pembeli:</label>
                    <select name="tipe_pembeli" id="tipe_pembeli_{{ $customer->id }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pembeli" {{ $customer->tipe_pembeli == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                        <option value="bengkel_langganan" {{ in_array($customer->tipe_pembeli, ['bengkel_langganan', 'bengkel', 'langganan']) ? 'selected' : '' }}>Bengkel Langganan</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="no_hp_{{ $customer->id }}" class="block font-semibold mb-1">No HP:</label>
                <input type="text" name="no_hp" id="no_hp_{{ $customer->id }}" value="{{ $customer->no_hp }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan no HP" onkeypress="return event.charCode >= 48 && event.charCode <= 57" />
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" @click="$dispatch('close-modal', 'edit-customer-modal-{{ $customer->id }}')"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-modal>
@endforeach

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to delete buttons
            document.querySelectorAll('.btn-delete-customer').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const namaCustomer = this.getAttribute('data-nama');
                    confirmDeleteCustomer(id, namaCustomer);
                });
            });
        });

        // Handle Edit Customer Form Submit dengan AJAX validasi
        let editFormsValidated = {};
        
        document.addEventListener('submit', function(e) {
            if (e.target.action && e.target.action.includes('/customer/') && e.target.method.toUpperCase() === 'POST') {
                // Check if it's an edit form (has PUT method)
                const methodInput = e.target.querySelector('input[name="_method"]');
                if (methodInput && methodInput.value === 'PUT') {
                    const formId = e.target.id || 'customer-form-' + Date.now();
                    e.target.id = formId;
                    
                    console.log('Edit customer form submitted:', formId); // Debug log
                    
                    if (editFormsValidated[formId]) {
                        // Sudah divalidasi, biarkan submit normal
                        console.log('Form already validated, submitting normally'); // Debug log
                        delete editFormsValidated[formId]; // Reset flag
                        return true;
                    }
                    
                    e.preventDefault();
                    console.log('Form prevented, checking via AJAX'); // Debug log
                    
                    const form = e.target;
                    const formData = new FormData(form);
                    
                    // Show loading
                    Swal.fire({
                        title: 'Memeriksa...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status); // Debug log
                        return response.json();
                    })
                    .then(data => {
                        Swal.close();
                        
                        console.log('Response data:', data); // Debug log
                        console.log('Has no_changes flag:', data.no_changes); // Debug log
                        
                        if (data.success) {
                            // Get customer ID from form action
                            const customerId = form.action.split('/').pop();
                            
                            // Close modal
                            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-customer-modal-' + customerId }));
                            
                            // Check if there are no changes
                            if (data.no_changes) {
                                console.log('No changes detected, showing info popup'); // Debug log
                                
                                Swal.fire({
                                    title: 'Info',
                                    text: data.message,
                                    icon: 'info',
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                            
                            // Show success popup and reload
                            console.log('Changes detected, showing success popup'); // Debug log
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload the page to show updated data
                                window.location.reload();
                            });
                        } else {
                            // Tampilkan popup error
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan saat memperbarui customer',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat memperbarui customer',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            }
        });

        function confirmDeleteCustomer(id, namaCustomer) {
            Swal.fire({
                title: 'Apakah Anda yakin menghapus customer?',
                text: `Customer "${namaCustomer}" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }

        // Filter tipe pembeli function dengan event delegation
        document.addEventListener('click', function(e) {
            // Handle tipe filter buttons
            if (e.target.classList.contains('tipe-filter-btn')) {
                document.getElementById('tipe_pembeli_input').value = e.target.getAttribute('data-tipe');
                document.getElementById('filter-form').submit();
            }
        });

        // Auto-submit search form on input (debounced)
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('filter-form').submit();
                }, 500); // Wait 500ms after user stops typing
            });
        }
    </script>
@endpush
