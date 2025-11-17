<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Reusable SEO component --}}
    <x-seo :title="trim('SISTEM AMKAS '.View::yieldContent('title'))" :description="View::yieldContent('meta_description')" />
    @stack('head')

    <title>SISTEM AMKAS @yield('title')</title>

    <!-- Icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts & early preconnects (keep to <=2 to avoid Lighthouse warning) -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <!-- Preload critical font weights (FigTree 400/500/600) -->
    <link rel="preload" href="https://fonts.bunny.net/figtree/files/figtree-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/figtree/files/figtree-latin-500-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/figtree/files/figtree-latin-600-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Alpine.js (use trusted CDN, defer already default) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style id="critical-css">
        /* Critical CSS: layout skeleton, header, sidebar, KPI cards (keep minimal) */
        html { scroll-behavior: smooth; }
        body { min-height:100vh; overflow-x:hidden; background:#f8fafc; }
        .bg-gradient-to-r.from-slate-800.to-slate-900 { background:linear-gradient(to right,#1e293b,#0f172a); }
        .shadow-lg { box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1); }
        .rounded-xl { border-radius:0.75rem; }
        .p-6 { padding:1.5rem; }
        header { will-change:transform; }
        .kpi-card { display:flex; flex-direction:column; gap:.5rem; padding:1rem 1.25rem; border-radius:.75rem; background:#fff; box-shadow:0 2px 4px rgba(0,0,0,.04); }
        .kpi-value { font-size:1.125rem; font-weight:700; }
        .kpi-label { font-size:.75rem; font-weight:600; letter-spacing:.02em; text-transform:uppercase; color:#64748b; }
        @media (min-width:1024px){ .lg\:ml-72 { margin-left:18rem; } }
        /* Fade-in animation for main content to reduce perceived load time */
        #main-content { animation:fadeIn .25s ease-in; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    </style>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Prevent white flash during page transitions */
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Loading overlay animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .loading-overlay {
            animation: fadeIn 0.2s ease-in-out;
        }
    </style>

    <!-- SweetAlert2 (defer to avoid blocking parsing) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    
    <!-- Force all SweetAlert2 confirm buttons to green (after library is ready) -->
    <script defer>
        // Defer SweetAlert patch to idle time to avoid blocking initial parse
        (function attachSwalPatch(){
            function patch(){
                if (typeof Swal === 'undefined' || !Swal.fire) return false;
                const originalFire = Swal.fire.bind(Swal);
                Swal.fire = function(options) {
                    if (typeof options === 'string') {
                        return originalFire.apply(this, arguments);
                    }
                    options = options || {};
                    // Only set default green if no confirmButtonColor is specified
                    if (!options.confirmButtonColor) {
                        options.confirmButtonColor = '#10b981';
                    }
                    return originalFire(options);
                };
                return true;
            }
            const schedule = () => {
                if (patch()) return; // already patched
                if (window.requestIdleCallback) {
                    requestIdleCallback(() => patch(), { timeout: 3000 });
                } else {
                    setTimeout(patch, 1200);
                }
            };
            window.addEventListener('load', schedule);
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    // Add a page-specific class for JS gating
    $routeName = optional(request()->route())->getName();
    $bodyClasses = 'font-sans bg-gray-50 antialiased';
    if($routeName === 'transaksi.index') { $bodyClasses .= ' page-transaksi'; }
@endphp
<body class="{{ $bodyClasses }}" x-data="appData()" x-cloak>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:m-2 focus:p-2 focus:bg-white focus:text-black rounded shadow">Lewati ke konten utama</a>
    <!-- Overlay (Mobile & Desktop Transaksi) -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black bg-opacity-50 z-40" 
         :class="{ 'lg:hidden': !isTransaksiPage }"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar -->
    <aside 
         @click.stop
         class="fixed top-0 left-0 bottom-0 w-72 bg-gradient-to-b from-slate-800 via-slate-900 to-slate-950 shadow-2xl flex flex-col z-50 transform transition-transform duration-300 ease-in-out"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <!-- Header Sidebar with Logo -->
        <div class="p-6 border-b border-slate-700/50 bg-gradient-to-r from-slate-800/50 to-slate-700/50 relative">
            {{-- Close button for mobile (all pages) --}}
            <button @click="sidebarOpen = false" aria-label="Tutup menu samping" 
                    class="absolute top-4 right-4 p-1.5 hover:bg-slate-700/50 rounded-lg transition-colors"
                    :class="{ 'lg:hidden': !isTransaksiPage }">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <div class="flex items-center justify-center space-x-3 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-slate-600 to-slate-700 rounded-xl flex items-center justify-center shadow-lg ring-2 ring-slate-600/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">AMKAS</h1>
            </div>
            <p class="text-sm text-slate-300 text-center font-medium tracking-wide">Sistem Kasir Modern</p>
        </div>

        <!-- Scrollable Menu Area -->
        <div class="flex-1 overflow-y-auto p-5 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
            @php
                $user = auth()->user();
            @endphp

            <!-- Profile Section -->
            <div class="mb-6 p-4 bg-gradient-to-br from-slate-800/80 to-slate-900/80 rounded-xl border border-slate-700/50 shadow-xl backdrop-blur-sm hover:shadow-slate-700/20 transition-all duration-300">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-slate-600 to-slate-700 rounded-full flex items-center justify-center ring-2 ring-slate-700/50 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-slate-900"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-white truncate">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400 font-medium">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-700/50 text-slate-300 border border-slate-600/50">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            @if($user->role === 'kasir')
                <nav class="space-y-2">
                    <!-- Dashboard Link -->
                    <button type="button" @click.prevent="loadPage('{{ route('dashboard') }}', 'dashboard')"
                       :class="currentPage === 'dashboard' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'dashboard' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </button>

                    <!-- Kelola Data Customer -->
                    <button type="button" @click.prevent="loadPage('{{ route('customer.index') }}', 'customer.index')"
                       :class="currentPage === 'customer.index' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'customer.index' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span>Kelola Data Customer</span>
                    </button>

                    <!-- Transaksi -->
                    <button type="button" @click.prevent="loadPage('{{ route('transaksi.index') }}', 'transaksi.index')"
                       :class="currentPage === 'transaksi.index' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'transaksi.index' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span>Transaksi</span>
                    </button>

                    <button type="button" @click.prevent="loadPage('{{ url('barang-return') }}', 'barang-return')"
                       :class="currentPage === 'barang-return' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'barang-return' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                        </div>
                        <span>Barang Return</span>
                    </button>
                </nav>

            @elseif($user->role === 'owner')
                <nav class="space-y-2">
                    <!-- Dashboard Link -->
                    <button type="button" @click="window.location.href='{{ route('dashboard') }}'"
                       :class="currentPage === 'dashboard' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'dashboard' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </button>

                    <!-- Kelola Data Barang -->
                    <button type="button" @click.prevent="loadPage('{{ route('barang.index') }}', 'barang.index')"
                       :class="currentPage === 'barang.index' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'barang.index' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span>Kelola Data Barang</span>
                    </button>
                    

                    <button type="button" @click.prevent="updateCustomerViewed(); loadPage('{{ url('data-customer') }}', 'data-customer')"
                       :class="currentPage === 'data-customer' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <span class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'data-customer' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span>Data Customer</span>
                        </span>
                        @php
                            $newCustomerCount = \App\Models\Customer::where('created_at', '>', auth()->user()->last_viewed_customer_at ?? '1970-01-01 00:00:00')->count();
                        @endphp
                        @if($newCustomerCount > 0)
                            <span id="customer-notification-badge" class="bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-lg shadow-red-500/30 animate-pulse">{{ $newCustomerCount }}</span>
                        @endif
                    </button>

                    <!-- Kelola Data User -->
                    <button type="button" @click.prevent="loadPage('{{ route('user.index') }}', 'user.index')"
                       :class="currentPage === 'user.index' ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 scale-[1.02] border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white hover:translate-x-1'"
                       class="group flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 w-full text-left">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage === 'user.index' ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span>Kelola Data User</span>
                    </button>

                    <!-- Laporan Dropdown -->
                    <div x-data="{ open: currentPage.startsWith('owner.laporan') }" class="relative">
                        <button @click="open = !open"
                                :class="currentPage.startsWith('owner.laporan') ? 'bg-gradient-to-r from-slate-700 to-slate-800 text-white shadow-lg shadow-slate-700/50 border border-slate-600/50' : 'text-slate-300 hover:bg-slate-800/50 hover:text-white'"
                                class="group flex justify-between items-center w-full px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                            <span class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors" :class="currentPage.startsWith('owner.laporan') ? 'bg-white/20' : 'bg-slate-800/50 group-hover:bg-slate-700/50'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span>Laporan</span>
                            </span>
                            <svg :class="{'rotate-90': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="mt-2 ml-12 space-y-1 border-l-2 border-slate-700/50 pl-4">
                            <button type="button" @click.prevent="loadPage('{{ route('owner.laporanPenjualan') }}', 'owner.laporanPenjualan')" 
                                    :class="currentPage === 'owner.laporanPenjualan' ? 'bg-slate-700/50 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/30'" 
                                    class="block w-full text-left px-3 py-2.5 text-sm rounded-lg transition-all duration-200">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Laporan Penjualan
                                </span>
                            </button>
                            <button type="button" @click.prevent="loadPage('{{ route('owner.laporanBarangReturn') }}', 'owner.laporanBarangReturn')" 
                                    :class="currentPage === 'owner.laporanBarangReturn' ? 'bg-slate-700/50 text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-slate-800/30'" 
                                    class="block w-full text-left px-3 py-2.5 text-sm rounded-lg transition-all duration-200">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    Laporan Barang Return
                                </span>
                            </button>
                        </div>
                    </div>
                </nav>
            @endif
        </div>

        <!-- Logout Button -->
        <div class="p-5 border-t border-slate-700/50 bg-slate-900/50">
            <button onclick="confirmLogout()"
                    class="group w-full bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 px-4 py-3 rounded-xl text-white text-sm font-semibold hover:shadow-lg hover:shadow-red-600/30 transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </button>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="min-h-screen flex flex-col transition-all duration-300" 
         :class="{ 'lg:ml-72': !isTransaksiPage }"
         @click="if(sidebarOpen && window.innerWidth < 1024) sidebarOpen = false">
        <!-- Header -->
        <header x-show="!isTransaksiPage" class="bg-gradient-to-r from-slate-800 to-slate-900 border-b border-slate-700/50 shadow-lg px-4 lg:px-6 py-4 lg:py-6" @click.stop>
            <div class="flex items-center justify-between relative">
                <!-- Mobile Menu Button (Semua halaman kecuali transaksi) -->
                <button @click.stop="sidebarOpen = true" aria-label="Buka menu utama" 
                        class="lg:hidden p-2.5 text-white bg-slate-700/50 hover:bg-slate-700 rounded-xl transition-all duration-200 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <h2 class="text-lg lg:text-xl font-bold text-white">SISTEM AMKAS</h2>
                </div>

            </div>
        </header>
        
        <!-- Transaksi Header dengan tombol sidebar -->
        <header x-show="isTransaksiPage" class="bg-gradient-to-r from-slate-800 to-slate-900 border-b border-slate-700/50 shadow-lg px-4 lg:px-6 py-3 lg:py-4" @click.stop>
            <div class="flex items-center justify-between">
                <!-- Tombol buka sidebar untuk halaman transaksi (mobile & desktop) -->
                <button @click.stop="sidebarOpen = true" aria-label="Buka menu transaksi" 
                        class="p-2 hover:bg-slate-700/50 rounded-lg transition-colors group">
                    <svg class="w-6 h-6 text-white group-hover:text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Center: Title dengan Icon -->
                <h1 class="text-lg lg:text-2xl font-bold text-white flex items-center gap-2 lg:gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 lg:w-7 lg:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="hidden sm:inline">SISTEM AMKAS</span>
                    <span class="sm:hidden">Kasir - POS</span>
                </h1>
                
                <!-- Right: Timestamp -->
                <div class="flex items-center gap-3">
                    <span class="text-slate-300 text-xs lg:text-sm" x-data x-text="new Date().toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })"></span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1" :class="isTransaksiPage ? '' : 'p-6'">
            <div id="main-content" :class="isTransaksiPage ? '' : 'min-h-[calc(100vh-200px)]'">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer x-show="!isTransaksiPage" class="bg-white border-t border-gray-200 text-center text-sm text-gray-600 py-4">
            &copy; 2025 AMKAS. Sistem Kasir Modern.
        </footer>
    </div>

    <script>
        function appData() {
            return {
                sidebarOpen: false,
                currentPage: '{{ request()->route() ? request()->route()->getName() : 'dashboard' }}',
                isTransaksiPage: false,
                
                init() {
                    // Check if current page is transaksi
                    this.checkTransaksiPage();
                    
                    // Set initial sidebar state based on screen size and page type
                    this.updateSidebarState();
                    
                    // Handle window resize
                    window.addEventListener('resize', () => {
                        this.updateSidebarState();
                    });
                    
                    // Store instance globally for access from transaksi page
                    window.appDataInstance = this;
                },
                
                updateSidebarState() {
                    // Di halaman transaksi, sidebar selalu tertutup saat load
                    if (this.isTransaksiPage) {
                        this.sidebarOpen = false;
                    } else {
                        // Di halaman lain, sidebar terbuka di desktop, tertutup di mobile
                        this.sidebarOpen = window.innerWidth >= 1024;
                    }
                },
                
                checkTransaksiPage() {
                    this.isTransaksiPage = this.currentPage === 'transaksi.index' || window.location.pathname.includes('/transaksi');
                },
                
                // Global state with localStorage persistence
                globalState: {
                    cart: JSON.parse(localStorage.getItem('amkas_cart') || '[]'),
                    transactions: JSON.parse(localStorage.getItem('amkas_transactions') || '[]'),
                    userRole: '{{ auth()->user()->role }}',
                    lastViewedCustomer: '{{ auth()->user()->last_viewed_customer_at }}',
                    lastViewedBarang: '{{ auth()->user()->last_viewed_barang_at }}'
                },

                // Save global state to localStorage
                saveState() {
                    localStorage.setItem('amkas_cart', JSON.stringify(this.globalState.cart));
                    localStorage.setItem('amkas_transactions', JSON.stringify(this.globalState.transactions));
                },

                // Add item to cart
                addToCart(item) {
                    this.globalState.cart.push(item);
                    this.saveState();
                },

                // Remove item from cart
                removeFromCart(index) {
                    this.globalState.cart.splice(index, 1);
                    this.saveState();
                },

                // Clear cart
                clearCart() {
                    this.globalState.cart = [];
                    this.saveState();
                },

                // Add transaction
                addTransaction(transaction) {
                    this.globalState.transactions.push(transaction);
                    this.saveState();
                },

                updateCustomerViewed() {
                    // Immediately hide the badge with animation
                    const badge = document.getElementById('customer-notification-badge');
                    if (badge) {
                        badge.style.transition = 'all 0.3s ease-out';
                        badge.style.opacity = '0';
                        badge.style.transform = 'scale(0)';
                        setTimeout(() => badge.remove(), 300);
                    }

                    // Send request to server to update timestamp
                    fetch('{{ route('owner.dataCustomer') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ action: 'update_viewed' })
                    })
                    .then(response => {
                        if (response.ok) {
                            // Update global state
                            this.globalState.lastViewedCustomer = new Date().toISOString();
                        }
                    })
                    .catch(error => console.error('Error updating customer viewed status:', error));
                },

                loadPage(url, routeName) {
                    // Check if navigating from/to transaksi page - use normal navigation
                    const isGoingToTransaksi = routeName === 'transaksi.index';
                    const isComingFromTransaksi = this.currentPage === 'transaksi.index';
                    
                    if (isGoingToTransaksi || isComingFromTransaksi) {
                        // Directly navigate - simplest approach
                        window.location.href = url;
                        return;
                    }
                    
                    // Update current page
                    this.currentPage = routeName;
                    
                    // Check if it's transaksi page
                    this.checkTransaksiPage();
                    
                    // Update sidebar state based on new page
                    this.updateSidebarState();

                    // Show loading indicator
                    const mainContent = document.getElementById('main-content');
                    mainContent.innerHTML = `
                        <div class="flex items-center justify-center h-64">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                        </div>
                    `;

                    // Load content via AJAX with timeout
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        },
                        signal: controller.signal
                    })
                    .then(response => {
                        clearTimeout(timeoutId);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        // Extract the content part from the full HTML response
                        // Ensure HTML has DOCTYPE to prevent Quirks Mode
                        if (!html.trim().toLowerCase().startsWith('<!doctype')) {
                            html = '<!DOCTYPE html>' + html;
                        }
                        
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.querySelector('#main-content');

                        if (newContent) {
                            mainContent.innerHTML = newContent.innerHTML;
                        } else {
                            // If no #main-content found, assume the whole body is content
                            const bodyContent = doc.body.innerHTML;
                            mainContent.innerHTML = bodyContent;
                        }

                        // Update page title
                        const title = doc.querySelector('title');
                        if (title) {
                            document.title = title.textContent;
                        }

                        // Check for session flash messages in the new content and ensure they're visible
                        this.$nextTick(() => {
                            const alerts = ['success-alert', 'info-alert', 'error-alert', 'warning-alert'];
                            alerts.forEach(alertId => {
                                const alert = document.getElementById(alertId);
                                if (alert) {
                                    // Make sure alert is visible
                                    alert.style.display = 'block';
                                    alert.style.opacity = '1';
                                }
                            });
                        });

                        // Update URL without page reload
                        window.history.pushState({page: routeName}, '', url);

                        // Re-initialize Alpine components in the new content
                        this.$nextTick(() => {
                            try {
                                Alpine.initTree(mainContent);
                            } catch (e) {
                                console.error('Error initializing Alpine:', e);
                            }
                            
                            // Dispatch custom event for search/filter initialization
                            setTimeout(() => {
                                window.dispatchEvent(new CustomEvent('content-loaded'));
                                document.dispatchEvent(new CustomEvent('page-loaded'));
                                // Manually trigger alert initialization to ensure it works
                                if (typeof initAlerts === 'function') {
                                    initAlerts();
                                }
                            }, 100);
                        });

                        // Re-initialize charts if on dashboard
                        if (routeName === 'dashboard') {
                            setTimeout(() => {
                                const chartContainer = mainContent.querySelector('[x-data="chartManager()"]');
                                if (chartContainer && chartContainer._x_dataStack) {
                                    const chartManager = chartContainer._x_dataStack[0];
                                    if (chartManager && typeof chartManager.initCharts === 'function') {
                                        chartManager.initCharts();
                                    }
                                } else {
                                    // Fallback: try to find and initialize chartManager globally
                                    const alpineData = Alpine.store('appData');
                                    if (alpineData && alpineData.currentPage === 'dashboard') {
                                        const chartEl = mainContent.querySelector('[x-data="chartManager()"]');
                                        if (chartEl) {
                                            Alpine.initTree(chartEl);
                                            // After Alpine init, call initCharts
                                            setTimeout(() => {
                                                const newChartContainer = mainContent.querySelector('[x-data="chartManager()"]');
                                                if (newChartContainer && newChartContainer._x_dataStack) {
                                                    const newChartManager = newChartContainer._x_dataStack[0];
                                                    if (newChartManager && typeof newChartManager.initCharts === 'function') {
                                                        newChartManager.initCharts();
                                                    }
                                                }
                                            }, 100);
                                        }
                                    }
                                }
                            }, 200); // Increased delay to ensure DOM is fully ready
                        }

                        // Listen for custom events that might trigger chart re-initialization
                        // This handles cases where forms are submitted via AJAX and need to refresh charts
                        document.addEventListener('chartDataChanged', () => {
                            if (routeName === 'dashboard') {
                                setTimeout(() => {
                                    const chartContainer = mainContent.querySelector('[x-data="chartManager()"]');
                                    if (chartContainer && chartContainer._x_dataStack) {
                                        const chartManager = chartContainer._x_dataStack[0];
                                        if (chartManager && typeof chartManager.initCharts === 'function') {
                                            chartManager.initCharts();
                                        }
                                    }
                                }, 100);
                            }
                        });

                        // Listen for load-page events dispatched from forms
                        document.addEventListener('load-page', (event) => {
                            const { url, routeName: newRouteName } = event.detail;
                            this.loadPage(url, newRouteName);
                        });
                    })
                    .catch(error => {
                        clearTimeout(timeoutId);
                        console.error('Error loading page:', error);
                        
                        // Handle abort/timeout specifically
                        if (error.name === 'AbortError') {
                            mainContent.innerHTML = `
                                <div class="flex items-center justify-center h-64">
                                    <div class="text-center">
                                        <div class="text-orange-500 text-lg font-semibold mb-2">
                                            <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Request Timeout
                                        </div>
                                        <div class="text-gray-600 mb-4">Halaman membutuhkan waktu terlalu lama untuk dimuat.</div>
                                        <button onclick="window.location.reload()" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                            Muat Ulang Halaman
                                        </button>
                                    </div>
                                </div>
                            `;
                        } else {
                            mainContent.innerHTML = `
                                <div class="flex items-center justify-center h-64">
                                    <div class="text-center">
                                        <div class="text-red-500 text-lg font-semibold mb-2">
                                            <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Error
                                        </div>
                                        <div class="text-gray-600 mb-4">Gagal memuat konten halaman. Silakan coba lagi.</div>
                                        <button onclick="window.location.href='${url}'" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors mr-2">
                                            Coba Lagi
                                        </button>
                                        <button onclick="window.location.href='{{ route('dashboard') }}'" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                            Ke Dashboard
                                        </button>
                                    </div>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                    });
                }
            }
        }

        // Handle browser back/forward navigation
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.page) {
                // Reload the page content for the state
                const app = Alpine.store('appData');
                if (app) {
                    app.loadPage(window.location.href, event.state.page);
                }
            }
        });

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

    {{-- Global Search and Filter Script - Compatible with Alpine.js SPA --}}
    <script>
        // Global function to initialize search and filter functionality
        function initSearchAndFilter() {
            // Remove existing listeners to prevent duplicates
            const searchInput = document.querySelector('input[name="search"]');
            const searchBarangInput = document.querySelector('input[name="search_barang"]');
            const filterForm = document.getElementById('filter-form');

            if (!filterForm) return; // Exit if no filter form on current page

            // Clone and replace to remove all event listeners
            if (searchInput) {
                const newSearchInput = searchInput.cloneNode(true);
                searchInput.parentNode.replaceChild(newSearchInput, searchInput);
                
                // Add debounced auto-submit for search
                let searchTimeout;
                newSearchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500);
                });
            }

            if (searchBarangInput) {
                const newSearchBarangInput = searchBarangInput.cloneNode(true);
                searchBarangInput.parentNode.replaceChild(newSearchBarangInput, searchBarangInput);
                
                // Add debounced auto-submit for search barang
                let searchBarangTimeout;
                newSearchBarangInput.addEventListener('input', function() {
                    clearTimeout(searchBarangTimeout);
                    searchBarangTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500);
                });
            }

            // Handle filter buttons (category, tipe, payment, etc.)
            document.addEventListener('click', function filterClickHandler(e) {
                // Category filter
                if (e.target.classList.contains('category-filter-btn')) {
                    e.preventDefault();
                    const kategoriInput = document.getElementById('kategori-input');
                    if (kategoriInput && filterForm) {
                        kategoriInput.value = e.target.getAttribute('data-category');
                        filterForm.submit();
                    }
                }
                
                // Tipe pembeli filter
                if (e.target.classList.contains('tipe-filter-btn')) {
                    e.preventDefault();
                    const tipeInput = document.getElementById('tipe_pembeli_input');
                    if (tipeInput && filterForm) {
                        tipeInput.value = e.target.getAttribute('data-tipe');
                        filterForm.submit();
                    }
                }
                
                // Payment filter
                if (e.target.classList.contains('payment-filter-btn')) {
                    e.preventDefault();
                    const paymentInput = document.getElementById('tipe_pembayaran_input');
                    if (paymentInput && filterForm) {
                        paymentInput.value = e.target.getAttribute('data-payment');
                        filterForm.submit();
                    }
                }
                
                // Status filter
                if (e.target.classList.contains('status-filter-btn')) {
                    e.preventDefault();
                    const statusInput = document.getElementById('status_input');
                    if (statusInput && filterForm) {
                        statusInput.value = e.target.getAttribute('data-status');
                        filterForm.submit();
                    }
                }
            }, { once: false });

            // Handle onclick filterCategory for backward compatibility
            window.filterCategory = function(category) {
                const kategoriInput = document.getElementById('kategori-input');
                if (kategoriInput && filterForm) {
                    kategoriInput.value = category;
                    filterForm.submit();
                }
            };
        }

        // Global function to initialize alerts auto-dismiss
        window.initAlerts = function() {
            // Handle success alerts
            const successAlert = document.getElementById('success-alert');
            if (successAlert && !successAlert.dataset.initialized) {
                successAlert.dataset.initialized = 'true';
                showPopupAlert('success', successAlert.textContent.trim());
                successAlert.remove();
            }

            // Handle info alerts
            const infoAlert = document.getElementById('info-alert');
            if (infoAlert && !infoAlert.dataset.initialized) {
                infoAlert.dataset.initialized = 'true';
                showPopupAlert('info', infoAlert.textContent.trim());
                infoAlert.remove();
            }

            // Handle error alerts
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert && !errorAlert.dataset.initialized) {
                errorAlert.dataset.initialized = 'true';
                showPopupAlert('error', errorAlert.textContent.trim());
                errorAlert.remove();
            }

            // Handle warning alerts
            const warningAlert = document.getElementById('warning-alert');
            if (warningAlert && !warningAlert.dataset.initialized) {
                warningAlert.dataset.initialized = 'true';
                showPopupAlert('warning', warningAlert.textContent.trim());
                warningAlert.remove();
            }
        }

        // Function to show popup alert using SweetAlert2
        window.showPopupAlert = function(type, message) {
            const config = {
                success: {
                    icon: 'success',
                    title: 'Berhasil!',
                    confirmButtonColor: '#10b981'
                },
                info: {
                    icon: 'info',
                    title: 'Info',
                    confirmButtonColor: '#3b82f6'
                },
                error: {
                    icon: 'error',
                    title: 'Error!',
                    confirmButtonColor: '#ef4444'
                },
                warning: {
                    icon: 'warning',
                    title: 'Peringatan!',
                    confirmButtonColor: '#f59e0b'
                }
            };

            const alertConfig = config[type] || config.info;
            
            Swal.fire({
                icon: alertConfig.icon,
                title: alertConfig.title,
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: alertConfig.confirmButtonColor,
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show',
                    icon: 'swal2-icon-show'
                }
            });
        }

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initSearchAndFilter();
            initAlerts();
        });

        // Re-initialize after SPA navigation
        document.addEventListener('page-loaded', function() {
            setTimeout(() => {
                initSearchAndFilter();
                initAlerts();
            }, 150);
        });

        // Re-initialize after Alpine is ready
        document.addEventListener('alpine:initialized', function() {
            initSearchAndFilter();
            initAlerts();
        });

        // Listen for custom event from loadPage function
        window.addEventListener('content-loaded', function() {
            initSearchAndFilter();
            initAlerts();
        });
    </script>

    <!-- Global Flash Message Popup Handler -->
    <script>
        // Show popup for flash messages on initial page load
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6'
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
