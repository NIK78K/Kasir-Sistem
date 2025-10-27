<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Background with Gradient -->
        <div class="min-h-screen bg-gradient-to-br from-slate-800 to-slate-900 relative overflow-hidden">
        <!-- Gradient Orbs -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-slate-700 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-slate-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-slate-900 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

        <!-- Main Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-6xl">
                <!-- Main Card -->
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                    <!-- Header Section -->
                    <div class="text-center pt-12 pb-8 px-6 sm:pt-16 sm:pb-10 sm:px-12">
                        <!-- Brand Name -->
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white mb-4 animate-fade-in animation-delay-200 animation-fill-both">
                            AMKAS
                        </h1>
                        
                        <!-- Tagline -->
                        <p class="text-lg sm:text-xl lg:text-2xl text-slate-300 font-medium mb-3 animate-fade-in animation-delay-400 animation-fill-both">
                            Point of Sale Modern untuk Bisnis Anda
                        </p>
                        <p class="text-sm sm:text-base text-slate-400 max-w-2xl mx-auto animate-fade-in animation-delay-600 animation-fill-both">
                            Kelola bisnis retail Anda dengan sistem POS yang cepat, mudah, dan terpercaya
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="px-6 sm:px-12 pb-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                            <!-- Feature 1 -->
                            <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/15 hover:border-white/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-800 animation-fill-both">
                                <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">📦</div>
                                <h3 class="text-lg font-semibold text-white mb-2">Manajemen Barang</h3>
                                <p class="text-sm text-white/70 leading-relaxed">Kelola stok dan harga dengan mudah</p>
                            </div>

                            <!-- Feature 2 -->
                            <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/15 hover:border-white/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-900 animation-fill-both">
                                <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">⚡</div>
                                <h3 class="text-lg font-semibold text-white mb-2">Transaksi Cepat</h3>
                                <p class="text-sm text-white/70 leading-relaxed">Proses penjualan secara efisien</p>
                            </div>

                            <!-- Feature 3 -->
                            <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/15 hover:border-white/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-1000 animation-fill-both">
                                <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">👥</div>
                                <h3 class="text-lg font-semibold text-white mb-2">Multi User</h3>
                                <p class="text-sm text-white/70 leading-relaxed">Akses sesuai peran pengguna</p>
                            </div>

                            <!-- Feature 4 -->
                            <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/15 hover:border-white/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-1100 animation-fill-both">
                                <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">📊</div>
                                <h3 class="text-lg font-semibold text-white mb-2">Laporan Lengkap</h3>
                                <p class="text-sm text-white/70 leading-relaxed">Pantau performa bisnis real-time</p>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pb-8 animate-fade-in animation-delay-1200 animation-fill-both">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-slate-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-slate-800/50 transition-all duration-300 hover:-translate-y-1 w-full sm:w-auto">
                                        <span class="relative z-10 flex items-center gap-2">
                                            Dashboard
                                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </span>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-slate-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-slate-800/50 transition-all duration-300 hover:-translate-y-1 w-full sm:w-auto">
                                        <span class="relative z-10 flex items-center gap-2">
                                            Masuk
                                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </span>
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-white/10 backdrop-blur-sm border-2 border-white/20 rounded-xl hover:bg-white/20 hover:border-white/30 transition-all duration-300 hover:-translate-y-1 w-full sm:w-auto">
                                            <span class="relative z-10 flex items-center gap-2">
                                                Daftar
                                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="text-center mt-8 text-slate-400 text-sm animate-fade-in animation-delay-1400 animation-fill-both">
                    <p>&copy; {{ date('Y') }} AMKAS. Sistem Point of Sale Modern.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>