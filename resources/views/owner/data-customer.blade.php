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
        <h2 class="sr-only">Filter dan Pencarian</h2>

        {{-- Alert (Hidden - using global popup) --}}
        @if (session('success'))
            <div class="hidden">{{ session('success') }}</div>
        @endif

        {{-- Filter dan Search --}}
        <div class="mb-8">
            <form method="GET" action="{{ route('owner.dataCustomer') }}" id="filter-form" class="space-y-3">
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
                </div>

                <input type="hidden" name="tipe_pembeli" id="tipe_pembeli_input" value="{{ request('tipe_pembeli') }}">
            </form>
        </div>

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
                                    {{ in_array($customer->tipe_pembeli, ['bengkel_langganan', 'bengkel', 'langganan']) ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $customer->tipe_pembeli == 'bengkel_langganan' || $customer->tipe_pembeli == 'bengkel' || $customer->tipe_pembeli == 'langganan' ? 'Bengkel Langganan' : 'Pembeli' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach

                    {{-- Jika kosong --}}
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">
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
                        <h3 class="text-lg font-bold text-gray-900">{{ $customer->nama_customer }}</h3>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ in_array($customer->tipe_pembeli, ['bengkel_langganan', 'bengkel', 'langganan']) ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ $customer->tipe_pembeli == 'bengkel_langganan' || $customer->tipe_pembeli == 'bengkel' || $customer->tipe_pembeli == 'langganan' ? 'Bengkel Langganan' : 'Pembeli' }}
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
    </div>
@endsection

@push('scripts')
    <script>
        // Filter tipe pembeli function dengan event delegation
        document.addEventListener('click', function(e) {
            // Handle tipe filter buttons
            if (e.target.classList.contains('tipe-filter-btn')) {
                document.getElementById('tipe_pembeli_input').value = e.target.getAttribute('data-tipe');
                document.getElementById('filter-form').submit();
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
    </script>
@endpush
