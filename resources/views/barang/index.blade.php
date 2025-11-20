@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
@php
    // Preload first product image for better LCP if available
    $firstImage = optional($barangs->first())->gambar;
@endphp
@if($firstImage)
    @php
        $firstWebp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $firstImage);
        $hasFirstWebp = \Illuminate\Support\Facades\Storage::disk('public')->exists($firstWebp);
    @endphp
    @push('head')
        @if($hasFirstWebp)
            <link rel="preload" as="image" href="{{ asset('storage/' . $firstWebp) }}" imagesrcset="{{ asset('storage/' . $firstWebp) }}" />
        @else
            <link rel="preload" as="image" href="{{ asset('storage/' . $firstImage) }}" imagesrcset="{{ asset('storage/' . $firstImage) }}" />
        @endif
    @endpush
@endif
<div class="max-w-7xl mx-auto p-6">
    {{-- Banner Selamat Datang --}}
    <div class="mb-4 rounded-2xl p-6 shadow-lg bg-gray-700">
        <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
            📦 Data Barang
        </h1>
    </div>
    <h2 class="sr-only">Daftar Barang</h2>

    {{-- Alert (Hidden - using popup instead) --}}
    @if (session('success'))
        <div class="hidden" id="success-alert">{{ session('success') }}</div>
    @endif

    @if (session('info'))
        <div class="hidden" id="info-alert">{{ session('info') }}</div>
    @endif

    @if (session('error'))
        <div class="hidden" id="error-alert">{{ session('error') }}</div>
    @endif

    @if (session('warning'))
        <div class="hidden" id="warning-alert">{{ session('warning') }}</div>
    @endif

    {{-- Modal Create Barang --}}
{{-- Modal Create Barang --}}
<x-modal name="create-barang-modal" maxWidth="2xl" 
    x-on:close-modal.window="if ($event.detail === 'create-barang-modal') { $refs.createBarangForm?.reset(); }"
    x-on:open-modal.window="if ($event.detail === 'create-barang-modal') { $refs.createBarangForm?.reset(); }">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah Barang Baru</h2>

        <form x-ref="createBarangForm" id="create-barang-form" action="{{ route('barang.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="create-nama_barang" class="block font-semibold mb-1">Nama Barang:</label>
                <input id="create-nama_barang" type="text" name="nama_barang"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan nama barang" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="create-harga" class="block font-semibold mb-1">Harga Retail: <span class="text-red-500">*</span></label>
                    <input id="create-harga" type="number" name="harga" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan harga retail" />
                </div>

                <div>
                    <label for="create-harga_grosir" class="block font-semibold mb-1">Harga Grosir: <span class="text-red-500">*</span></label>
                    <input id="create-harga_grosir" type="number" name="harga_grosir" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan harga grosir" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="create-stok" class="block font-semibold mb-1">Stok:</label>
                    <input id="create-stok" type="number" name="stok" min="0"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan stok barang" />
                </div>

                <div>
                    <label for="create-kategori" class="block font-semibold mb-1">Kategori:</label>
                    <select id="create-kategori" name="kategori"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Sepeda Pacifik">Sepeda Pacifik</option>
                        <option value="Sepeda Listrik">Sepeda Listrik</option>
                        <option value="Ban">Ban</option>
                        <option value="Sepeda Stroller">Sepeda Stroller</option>
                        <option value="Sparepart">Sparepart</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="create-gambar" class="block font-semibold mb-1">Unggah Gambar Barang:</label>
                <input id="create-gambar" type="file" name="gambar" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                    @click="$refs.createBarangForm.reset(); $dispatch('close-modal', 'create-barang-modal')"
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

{{-- Modal Edit Barang --}}
@foreach($barangs as $barang)
<x-modal name="edit-barang-modal-{{ $barang->id }}" maxWidth="2xl">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Edit Barang</h2>

        <form id="edit-barang-form-{{ $barang->id }}" action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="edit-nama_barang-{{ $barang->id }}" class="block font-semibold mb-1">Nama Barang:</label>
                <input id="edit-nama_barang-{{ $barang->id }}" type="text" name="nama_barang" value="{{ $barang->nama_barang }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit-harga-{{ $barang->id }}" class="block font-semibold mb-1">Harga Retail: <span class="text-red-500">*</span></label>
                    <input id="edit-harga-{{ $barang->id }}" type="number" name="harga" value="{{ $barang->harga }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label for="edit-harga_grosir-{{ $barang->id }}" class="block font-semibold mb-1">Harga Grosir: <span class="text-red-500">*</span></label>
                    <input id="edit-harga_grosir-{{ $barang->id }}" type="number" name="harga_grosir" value="{{ $barang->harga_grosir }}" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit-stok-{{ $barang->id }}" class="block font-semibold mb-1">Stok:</label>
                    <input id="edit-stok-{{ $barang->id }}" type="number" name="stok" value="{{ $barang->stok }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label for="edit-kategori-{{ $barang->id }}" class="block font-semibold mb-1">Kategori:</label>
                    <select id="edit-kategori-{{ $barang->id }}" name="kategori"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Sepeda Pacifik" {{ $barang->kategori == 'Sepeda Pacifik' ? 'selected' : '' }}>Sepeda Pacifik</option>
                        <option value="Sepeda Listrik" {{ $barang->kategori == 'Sepeda Listrik' ? 'selected' : '' }}>Sepeda Listrik</option>
                        <option value="Ban" {{ $barang->kategori == 'Ban' ? 'selected' : '' }}>Ban</option>
                        <option value="Sepeda Stroller" {{ $barang->kategori == 'Sepeda Stroller' ? 'selected' : '' }}>Sepeda Stroller</option>
                        <option value="Sparepart" {{ $barang->kategori == 'Sparepart' ? 'selected' : '' }}>Sparepart</option>
                        <option value="Lainnya" {{ $barang->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="edit-gambar-{{ $barang->id }}" class="block font-semibold mb-1">Gambar:</label>
                <input id="edit-gambar-{{ $barang->id }}" type="file" name="gambar" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                    @click="$dispatch('close-modal', 'edit-barang-modal-{{ $barang->id }}')"
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

    {{-- Filter dan Search --}}
    <div class="mb-8">
        <form method="GET" action="{{ route('barang.index') }}" id="filter-form" class="space-y-3">
            {{-- Search Bar --}}
            <div class="relative">
                <label for="search-barang" class="sr-only">Cari barang</label>
                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input id="search-barang" type="text" name="search" placeholder="Cari Barang" value="{{ request('search') }}"
                    class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-gray-800 placeholder:text-gray-500" />
            </div>

            {{-- Category Filter Pills --}}
            <div class="flex flex-wrap gap-2 items-center">
                <button type="button" data-category="" class="category-filter-btn category-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ !request('kategori') ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua Produk
                </button>
                @foreach($allCategories as $category)
                    <button type="button" data-category="{{ $category }}" class="category-filter-btn category-pill px-4 py-2 rounded-full text-sm font-semibold transition {{ request('kategori') == $category ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $category }}
                    </button>
                @endforeach
                <div class="ml-auto">
                    <button type="button"
                        class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-semibold whitespace-nowrap"
                        @click="$dispatch('open-modal', 'create-barang-modal')">
                        Tambah Barang
                    </button>
                </div>
            </div>
            <input type="hidden" name="kategori" id="kategori-input" value="{{ request('kategori') }}" />
        </form>
    </div>



    {{-- Daftar Barang --}}
    @if($barangs->isEmpty())
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-16 px-4">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Barang Tidak Tersedia</h3>
            @if(request('kategori'))
                <p class="text-gray-500 text-center mb-4">
                    Tidak ada produk dalam kategori <span class="font-semibold">"{{ request('kategori') }}"</span>.
                </p>
            @elseif(request('search'))
                <p class="text-gray-500 text-center mb-4">
                    Tidak ditemukan barang dengan kata kunci <span class="font-semibold">"{{ request('search') }}"</span>.
                </p>
            @else
                <p class="text-gray-500 text-center mb-4">
                    Belum ada barang yang ditambahkan. Silakan tambahkan barang baru.
                </p>
            @endif
            <button type="button"
                class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-semibold"
                @click="$dispatch('open-modal', 'create-barang-modal')">
                Tambah Barang Baru
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-5">
        @foreach($barangs as $barang)
            <div id="barang-{{ $barang->id }}" class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-xl transition-all duration-200 hover:border-gray-300 group flex flex-col">
                {{-- Product Image/Icon --}}
                <div class="w-full aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg overflow-hidden flex items-center justify-center relative mb-4 group-hover:scale-105 transition-transform">
                    @if($barang->gambar)
                        @php $isFirst = $loop->first; @endphp
                    @php $webp = $barang->webpPath(); @endphp
                    <picture>
                        @if($webp)
                            <source type="image/webp" srcset="{{ asset('storage/' . $webp) }}">
                        @endif
                        <img src="{{ asset('storage/' . $barang->gambar) }}"
                             alt="{{ $barang->nama_barang }}"
                             @if($isFirst) fetchpriority="high" @endif
                             width="400" height="400"
                             decoding="async"
                             @if(!$isFirst) loading="lazy" @endif
                             class="object-cover w-full h-full" />
                    </picture>
                    @else
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    @endif
                    
                    {{-- Badge NEW --}}
                    @if($barang->isNew())
                        <div class="absolute top-2 left-2 bg-gradient-to-r from-green-500 to-green-600 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-lg">
                            NEW
                        </div>
                    @endif
                    
                    {{-- Badge Tidak Tersedia --}}
                    @if($barang->is_deleted)
                        <div class="absolute top-2 right-2 bg-gradient-to-r from-red-500 to-red-600 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-lg">
                            Tidak Tersedia
                        </div>
                    @endif
                </div>
                
                {{-- Product Name --}}
                <h3 class="text-base font-bold text-gray-900 mb-3 line-clamp-2 min-h-[3rem]">
                    {{ $barang->nama_barang }}
                </h3>
                
                {{-- Category Badge --}}
                <div class="mb-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-orange-100 text-orange-700">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $barang->kategori }}
                    </span>
                </div>
                
                {{-- Stock Badge --}}
                <div class="mb-3">
                    <span class="inline-flex items-center text-sm font-semibold {{ $barang->stok <= 5 ? 'text-red-700' : 'text-green-700' }}">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Stok: {{ $barang->stok }} pcs
                    </span>
                </div>
                
                {{-- Pricing --}}
                <div class="space-y-2 mb-4 flex-grow">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Retail:</span>
                        @if($barang->harga)
                            <span class="text-base font-bold text-gray-900">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Grosir:</span>
                        @if($barang->harga_grosir)
                            <span class="text-base font-bold text-blue-700">Rp {{ number_format($barang->harga_grosir, 0, ',', '.') }}</span>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </div>
                </div>
                
                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 gap-2 pt-3 border-t border-gray-100">
                    <button type="button"
                            @click="$dispatch('open-modal', 'edit-barang-modal-{{ $barang->id }}')"
                            class="flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                    <button type="button"
                            class="btn-delete-barang flex items-center justify-center px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors"
                            data-id="{{ $barang->id }}"
                            data-nama="{{ $barang->nama_barang }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                    <form id="delete-form-{{ $barang->id }}" action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Handle Create Barang Form Submit dengan AJAX validasi
    const createForm = document.getElementById('create-barang-form');
    let createFormValidated = false;
    
    createForm.addEventListener('submit', function(e) {
        if (createFormValidated) {
            // Sudah divalidasi, biarkan submit normal
            createFormValidated = false; // Reset flag
            return true;
        }
        
        e.preventDefault();
        
        const formData = new FormData(this);
        
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
        
        fetch(createForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                // Show success popup then reload
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Barang berhasil ditambahkan',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    // Reload to show new data
                    window.location.href = '{{ route('barang.index') }}';
                });
            } else {
                // Tampilkan popup error (nama barang sudah digunakan atau error lain)
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat menyimpan barang',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            
            // Tampilkan popup error
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan barang',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        });
    });

    // Handle Edit Barang Form Submit dengan AJAX validasi
    let editFormsValidated = {};
    
    document.addEventListener('submit', function(e) {
        if (e.target.id && e.target.id.startsWith('edit-barang-form-')) {
            const formId = e.target.id;
            
            console.log('Form submitted:', formId); // Debug log
            
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
                    // Close modal first
                    const barangId = formId.replace('edit-barang-form-', '');
                    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'edit-barang-modal-' + barangId }));
                    
                    // Check if there are no changes
                    if (data.no_changes) {
                        console.log('No changes detected, showing info popup'); // Debug log
                        
                        Swal.fire({
                            icon: 'info',
                            title: 'Info',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }
                    
                    // Show success popup and reload
                    console.log('Changes detected, showing success popup'); // Debug log
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        // Reload the page to show updated data
                        window.location.reload();
                    });
                } else {
                    // Tampilkan popup error (nama barang sudah digunakan atau error lain)
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat memperbarui barang',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                
                // Tampilkan popup error
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memperbarui barang',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });

    // Filter category function dengan event delegation
    document.addEventListener('click', function(e) {
        // Handle category filter buttons
        if (e.target.classList.contains('category-filter-btn')) {
            const category = e.target.getAttribute('data-category');
            document.getElementById('kategori-input').value = category;
            document.getElementById('filter-form').submit();
        }
        
        // Handle delete button clicks
        if (e.target.classList.contains('btn-delete-barang') || e.target.closest('.btn-delete-barang')) {
            const button = e.target.classList.contains('btn-delete-barang') ? e.target : e.target.closest('.btn-delete-barang');
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            confirmDeleteBarang(id, nama);
        }
    });

    // Auto-submit search form on input (debounced) - Real-time search
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

    // Function to show success alert
    function showSuccessAlert() {
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK'
            });
        }
    }
    
    // Function to show validation error popup (untuk non-AJAX form submit)
    function showValidationErrors() {
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        @endif
    }

    // Initialize on DOMContentLoaded and after dynamic content loads
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessAlert();
        showValidationErrors();
    });

    // Listen for custom event to re-initialize after page load
    document.addEventListener('page-loaded', function() {
        showSuccessAlert();
        showValidationErrors();
    });

    // Panggil langsung saat script diload
    showSuccessAlert();
    showValidationErrors();

    // Panggil juga setelah delay untuk memastikan (backup untuk SPA)
    setTimeout(function() {
        showSuccessAlert();
        showValidationErrors();
    }, 200);

    function confirmDeleteBarang(id, namaBarang) {
        Swal.fire({
            title: 'Apakah Anda yakin menghapus barang?',
            text: `Barang "${namaBarang}" akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX delete request
                fetch(`{{ url('barang') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#10b981',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the current page content via AJAX
                            window.dispatchEvent(new CustomEvent('load-page', { detail: { url: window.location.href, routeName: 'barang.index' } }));
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus barang',
                            icon: 'error',
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus barang',
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    // Ensure Alpine.js is working, add fallback for cancel buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit for Alpine to initialize
        setTimeout(() => {
            if (typeof Alpine === 'undefined') {
                console.warn('Alpine.js not loaded, using vanilla JS fallback');
            }
        }, 100);
    });
</script>
@endpush
