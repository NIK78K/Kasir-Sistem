<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem AMKas @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Alpine.js untuk dropdown -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen m-0 font-sans bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300">
    <!-- Sidebar -->
    <div class="sidebar w-64 bg-gray-900 text-white p-6 shadow-lg flex flex-col">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold tracking-wide">AMKas</h1>
            <p class="text-sm text-gray-400">Sistem Kasir Modern</p>
        </div>

        <!-- Profile -->
        @php
            $user = auth()->user();
        @endphp
        <div class="profile mb-8">
            <p class="font-bold text-lg mb-1">{{ $user->name }}</p>
            <p class="text-sm text-gray-400">{{ ucfirst($user->role) }}</p>
        </div>

        <!-- Menu -->
        @if($user->role === 'kasir')
            <ul class="space-y-2 text-sm">
                <!-- Dashboard Link -->
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold hover:text-blue-400 transition {{ request()->routeIs('dashboard') ? 'text-blue-400' : '' }}">
                    Dashboard
                </a>
                </li>
                <!-- Kelola Data Barang -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="flex justify-between items-center w-full font-semibold hover:text-blue-400 transition">
                        Kelola Data Barang
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <ul x-show="open" x-transition class="mt-2 ml-4 space-y-1">
                        <li><a href="{{ route('barang.create') }}" class="text-blue-400 hover:text-blue-600">Menambah Barang</a></li>
                        <li><a href="{{ route('barang.index') }}" class="text-blue-400 hover:text-blue-600">Data Barang</a></li>
                    </ul>
                </li>

                <!-- Kelola Data Customer -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="flex justify-between items-center w-full font-semibold hover:text-blue-400 transition">
                        Kelola Data Customer
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <ul x-show="open" x-transition class="mt-2 ml-4 space-y-1">
                        <li><a href="{{ route('customer.create') }}" class="text-blue-400 hover:text-blue-600">Menambah Data Customer</a></li>
                        <li><a href="{{ route('customer.index') }}" class="text-blue-400 hover:text-blue-600">Data Customer</a></li>
                    </ul>
                </li>

                <!-- Transaksi -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="flex justify-between items-center w-full font-semibold hover:text-blue-400 transition">
                        Transaksi
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <ul x-show="open" x-transition class="mt-2 ml-4 space-y-1">
                        <li><a href="{{ route('transaksi.index') }}" class="text-blue-400 hover:text-blue-600">Transaksi</a></li>
                        <li><a href="{{ route('transaksi.listBatal') }}" class="text-blue-400 hover:text-blue-600">Pembatalan Transaksi</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ url('barang-return') }}" class="font-semibold text-blue-400 hover:text-blue-600">Barang Return</a>
                </li>
            </ul>

        @elseif($user->role === 'owner')
            <ul class="space-y-2 text-sm">
                <!-- Dashboard Link -->
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold hover:text-blue-400 transition {{ request()->routeIs('dashboard') ? 'text-blue-400' : '' }}">
                    Dashboard
                    </a>
                </li>
                <li><a href="{{ url('data-barang') }}" class="text-blue-400 hover:text-blue-600">Data Barang</a></li>
                <li><a href="{{ url('data-customer') }}" class="text-blue-400 hover:text-blue-600">Data Customer</a></li>

                <!-- Kelola Data User -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="flex justify-between items-center w-full font-semibold hover:text-blue-400 transition">
                        Kelola Data User
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <ul x-show="open" x-transition class="mt-2 ml-4 space-y-1">
                        <li><a href="{{ route('user.create') }}" class="text-blue-400 hover:text-blue-600">Menambah Data User</a></li>
                        <li><a href="{{ route('user.index') }}" class="text-blue-400 hover:text-blue-600">Data User</a></li>
                    </ul>
                </li>

                <!-- Laporan -->
                <li x-data="{ open: false }">
                    <button @click="open = !open" class="flex justify-between items-center w-full font-semibold hover:text-blue-400 transition">
                        Laporan
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <ul x-show="open" x-transition class="mt-2 ml-4 space-y-1">
                        <li><a href="{{ route('owner.laporanPenjualan') }}" class="text-blue-400 hover:text-blue-600">Laporan Penjualan</a></li>
                        <li><a href="{{ route('owner.laporanBarangReturn') }}" class="text-blue-400 hover:text-blue-600">Laporan Barang Return</a></li>
                    </ul>
                </li>
            </ul>
        @endif
    </div>

    <!-- Content -->
    <div class="content flex-1 flex flex-col p-0">
        <!-- Header -->
       <header class="relative flex items-center bg-gray-800 text-white px-6 py-4 shadow-md border-b border-gray-700">
    <!-- Judul center -->
    <h2 class="absolute left-1/2 transform -translate-x-1/2 text-xl font-semibold">Sistem AMKas</h2>

    <!-- Profil di kanan -->
    <div class="ml-auto profile-header flex items-center space-x-4" x-data="{ showLogoutModal: false }">
        <span class="font-semibold text-gray-200 hover:text-white transition">
            {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
        </span>
        <button @click="showLogoutModal = true" class="bg-red-600 px-3 py-1 rounded text-white font-semibold hover:bg-red-700 shadow transition">
            Logout
        </button>

        <!-- Logout Confirmation Modal -->
        <div x-show="showLogoutModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.self="showLogoutModal = false">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full mx-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Logout</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin logout dari sistem?</p>
                <div class="flex justify-end space-x-3">
                    <button @click="showLogoutModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

        <!-- Main Content -->
        <main class="flex-1 mt-6 px-6">
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center text-sm text-gray-600 py-4 border-t mt-6">
            &copy; 2024 <span class="font-semibold text-gray-800">Anugrah Mandiri</span>. All rights reserved.
        </footer>
    </div>
</body>
</html>
