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

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50 antialiased">
    <!-- Sidebar -->
    <div class="fixed top-0 left-0 bottom-0 w-64 bg-gray-700 border-r border-gray-600 flex flex-col">
        <!-- Header Sidebar -->
        <div class="p-6 border-b border-gray-600 text-center">
            <h1 class="text-2xl font-semibold text-white">AMKAS</h1>
            <p class="text-sm text-gray-300 mt-1">Sistem Kasir</p>
        </div>

        <!-- Scrollable Menu Area -->
        <div class="flex-1 overflow-y-auto p-4">
            @php
                $user = auth()->user();
            @endphp

            <!-- Profile Section -->
            <div class="mb-6 p-4 bg-gray-600 rounded-lg border border-gray-500">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-white">{{ $user->name }}</p>
                        <p class="text-xs text-gray-300">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            @if($user->role === 'kasir')
                <nav class="space-y-1">
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-600 text-white' : 'text-gray-300 hover:bg-gray-600 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <!-- Kelola Data Barang -->
                    <div x-data="{ open: {{ request()->routeIs('barang.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Kelola Data Barang
                            </span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="mt-1 ml-8 space-y-1">
                            <a href="{{ route('barang.create') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Menambah Barang</a>
                            <a href="{{ route('barang.index') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Data Barang</a>
                        </div>
                    </div>

                    <!-- Kelola Data Customer -->
                    <div x-data="{ open: {{ request()->routeIs('customer.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Kelola Data Customer
                            </span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="mt-1 ml-8 space-y-1">
                            <a href="{{ route('customer.create') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Menambah Customer</a>
                            <a href="{{ route('customer.index') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Data Customer</a>
                        </div>
                    </div>

                    <!-- Transaksi -->
                    <div x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Transaksi
                            </span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="mt-1 ml-8 space-y-1">
                            <a href="{{ route('transaksi.index') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Transaksi</a>
                            <a href="{{ route('transaksi.listBatal') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Pembatalan Transaksi</a>
                        </div>
                    </div>

                    <a href="{{ url('barang-return') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Barang Return
                    </a>
                </nav>

            @elseif($user->role === 'owner')
                <nav class="space-y-1">
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-600 text-white' : 'text-gray-300 hover:bg-gray-600 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ url('data-barang') }}"
                       class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Data Barang
                        </span>
                        @php
                            $newBarangCount = \App\Models\Barang::where('created_at', '>', auth()->user()->last_viewed_barang_at ?? '1970-01-01 00:00:00')->count();
                        @endphp
                        @if($newBarangCount > 0)
                            <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-medium">{{ $newBarangCount }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ url('data-customer') }}"
                       class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Data Customer
                        </span>
                        @php
                            $newCustomerCount = \App\Models\Customer::where('created_at', '>', auth()->user()->last_viewed_customer_at ?? '1970-01-01 00:00:00')->count();
                        @endphp
                        @if($newCustomerCount > 0)
                            <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-medium">{{ $newCustomerCount }}</span>
                        @endif
                    </a>

                    <!-- Kelola Data User -->
                    <div x-data="{ open: {{ request()->routeIs('user.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Kelola Data User
                            </span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="mt-1 ml-8 space-y-1">
                            <a href="{{ route('user.create') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Menambah User</a>
                            <a href="{{ route('user.index') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Data User</a>
                        </div>
                    </div>

                    <!-- Laporan -->
                    <div x-data="{ open: {{ request()->routeIs('owner.laporan*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex justify-between items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Laporan
                            </span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="mt-1 ml-8 space-y-1">
                            <a href="{{ route('owner.laporanPenjualan') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Laporan Penjualan</a>
                            <a href="{{ route('owner.laporanBarangReturn') }}" class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-600 hover:text-white transition-colors">Laporan Barang Return</a>
                        </div>
                    </div>
                </nav>
            @endif
        </div>

        <!-- Logout Button -->
        <div class="p-4 border-t border-gray-600">
            <button onclick="confirmLogout()"
                    class="w-full bg-red-600 px-4 py-2.5 rounded-lg text-white text-sm font-medium hover:bg-red-700 transition-colors">
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
        <header class="bg-gray-700 border-b border-gray-600 px-6 py-2">
            <div class="flex items-center justify-between relative">
                <div></div>
                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <h2 class="text-lg font-semibold text-white">SISTEM AMKAS</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-3 px-3 py-1.5 bg-gray-600 rounded-lg">
                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-300">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="min-h-[calc(100vh-200px)]">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 text-center text-sm text-gray-600 py-4">
            &copy; 2024 <span class="font-medium text-gray-900">Anugrah Mandiri</span>. All rights reserved.
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
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
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

    @stack('scripts')
</body>
</html>
