<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SISTEM AMKAS @yield('title')</title>

    <!-- Icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Alpine.js untuk dropdown -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    

</head>
    <body class="font-sans bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300">
    <!-- Sidebar -->
    <div class="fixed top-0 left-0 bottom-0 w-64 text-white shadow-xl flex flex-col"
         style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
        <!-- Header Sidebar -->
        <div class="p-6 text-center border-b border-gray-700" style="border-color: rgba(255, 255, 255, 0.3);">
            <h1 class="text-2xl font-bold tracking-wide">AMKAS</h1>
            <p class="text-sm text-gray-200 mt-1">Sistem Kasir Modern</p>
        </div>

        <!-- Scrollable Menu Area -->
        <div class="flex-1 overflow-y-auto px-4 py-6">
            @php
                $user = auth()->user();
            @endphp

            <!-- Profile Section -->
            <div class="mb-6 p-4 rounded-lg shadow-inner bg-blue-500/30">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm md:text-base mb-1">{{ $user->name }}</p>
                        <p class="text-xs text-gray-200">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            @if($user->role === 'kasir')
                <ul class="space-y-3 text-sm">
                    <!-- Dashboard Link -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                            Dashboard
                        </a>
                    </li>
                    
                    <!-- Kelola Data Barang -->
                    <li x-data="{ open: {{ request()->routeIs('barang.*') ? 'true' : 'false' }} }" 
                        x-init="
                            $watch('open', value => {
                                if (value) $dispatch('close-other-dropdowns', { except: $el });
                            });
                            $root.addEventListener('close-other-dropdowns', event => {
                                if (event.detail.except !== $el) open = false;
                            });
                        ">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            <span>Kelola Data Barang</span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak class="mt-2 ml-6 space-y-2">
                            <li><a href="{{ route('barang.create') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Menambah Barang</a></li>
                            <li><a href="{{ route('barang.index') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Data Barang</a></li>
                        </ul>
                    </li>

                    <!-- Kelola Data Customer -->
                    <li x-data="{ open: {{ request()->routeIs('customer.*') ? 'true' : 'false' }} }" 
                        x-init="
                            $watch('open', value => {
                                if (value) $dispatch('close-other-dropdowns', { except: $el });
                            });
                            $root.addEventListener('close-other-dropdowns', event => {
                                if (event.detail.except !== $el) open = false;
                            });
                        ">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            <span>Kelola Data Customer</span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak class="mt-2 ml-6 space-y-2">
                            <li><a href="{{ route('customer.create') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Menambah Customer</a></li>
                            <li><a href="{{ route('customer.index') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Data Customer</a></li>
                        </ul>
                    </li>

                    <!-- Transaksi -->
                    <li x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }" 
                        x-init="
                            $watch('open', value => {
                                if (value) $dispatch('close-other-dropdowns', { except: $el });
                            });
                            $root.addEventListener('close-other-dropdowns', event => {
                                if (event.detail.except !== $el) open = false;
                            });
                        ">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            <span>Transaksi</span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak class="mt-2 ml-6 space-y-2">
                            <li><a href="{{ route('transaksi.index') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Transaksi</a></li>
                            <li><a href="{{ route('transaksi.listBatal') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Pembatalan Transaksi</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ url('barang-return') }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            Barang Return
                        </a>
                    </li>
                </ul>

            @elseif($user->role === 'owner')
                <ul class="space-y-3 text-sm">
                    <!-- Dashboard Link -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                            Dashboard
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ url('data-barang') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            Data Barang
                            @php
                                $newBarangCount = \App\Models\Barang::where('created_at', '>', auth()->user()->last_viewed_barang_at ?? '1970-01-01 00:00:00')->count();
                            @endphp
                            @if($newBarangCount > 0)
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $newBarangCount }}+</span>
                            @endif
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ url('data-customer') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            Data Customer
                            @php
                                $newCustomerCount = \App\Models\Customer::where('created_at', '>', auth()->user()->last_viewed_customer_at ?? '1970-01-01 00:00:00')->count();
                            @endphp
                            @if($newCustomerCount > 0)
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $newCustomerCount }}+</span>
                            @endif
                        </a>
                    </li>

                    <!-- Kelola Data User -->
                    <li x-data="{ open: {{ request()->routeIs('user.*') ? 'true' : 'false' }} }" 
                        x-init="
                            $watch('open', value => {
                                if (value) $dispatch('close-other-dropdowns', { except: $el });
                            });
                            $root.addEventListener('close-other-dropdowns', event => {
                                if (event.detail.except !== $el) open = false;
                            });
                        ">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            <span>Kelola Data User</span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak class="mt-2 ml-6 space-y-2">
                            <li><a href="{{ route('user.create') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Menambah User</a></li>
                            <li><a href="{{ route('user.index') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Data User</a></li>
                        </ul>
                    </li>

                    <!-- Laporan -->
                    <li x-data="{ open: {{ request()->routeIs('owner.laporan*') ? 'true' : 'false' }} }" 
                        x-init="
                            $watch('open', value => {
                                if (value) $dispatch('close-other-dropdowns', { except: $el });
                            });
                            $root.addEventListener('close-other-dropdowns', event => {
                                if (event.detail.except !== $el) open = false;
                            });
                        ">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 rounded-lg font-semibold transition hover:bg-gray-800 text-gray-300">
                            <span>Laporan</span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <ul x-show="open" x-transition x-cloak class="mt-2 ml-6 space-y-2">
                            <li><a href="{{ route('owner.laporanPenjualan') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Laporan Penjualan</a></li>
                            <li><a href="{{ route('owner.laporanBarangReturn') }}" class="block text-blue-400 hover:text-blue-300 py-1 transition">Laporan Barang Return</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>

        <!-- Logout Button di Bottom -->
        <div class="p-4 border-t border-gray-700">
            <button onclick="confirmLogout()"
                    class="w-full bg-red-600 px-4 py-2 rounded-lg text-white font-semibold hover:bg-red-700 shadow transition">
                Logout
            </button>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

        <!-- Main Content Area -->
        <div class="min-h-screen flex flex-col ml-64">
        <!-- Header -->
        <header class="text-white px-4 md:px-6 py-4 shadow-md sticky top-0 z-30" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
            <div class="relative flex items-center justify-center">
                <h2 class="text-xl font-semibold hidden sm:block">SISTEM AMKAS</h2>
                <div class="absolute right-4 md:right-6 flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-200 hidden sm:block">
                            {{ auth()->user()->name }} <span class="text-gray-300">({{ ucfirst(auth()->user()->role) }})</span>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-6">
            <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 min-h-[calc(100vh-200px)]">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white text-center text-sm text-gray-600 py-4 border-t shadow-inner px-4 md:px-0">
            &copy; 2024 <span class="font-semibold text-gray-800">Anugrah Mandiri</span>. All rights reserved.
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.confirmLogout = function() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Anda akan keluar dari sistem!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Keluar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            };
        });
    </script>
</body>
</html>
