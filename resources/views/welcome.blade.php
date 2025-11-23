<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AMKAS</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <!-- Background with Gradient -->
    <div class="min-h-screen bg-gradient-to-br from-slate-800 to-slate-900 relative overflow-hidden">
        <!-- Gradient Orbs -->
        <div class="absolute top-0 left-0 w-64 h-64 sm:w-96 sm:h-96 bg-slate-700 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 right-0 w-64 h-64 sm:w-96 sm:h-96 bg-slate-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 w-64 h-64 sm:w-96 sm:h-96 bg-slate-900 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

        <!-- Main Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
            <div class="w-full max-w-6xl">
                <!-- Main Card -->
                <div class="bg-slate-800/90 backdrop-blur-md rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-700/50 overflow-hidden">
                    <!-- Header Section -->
                    <div class="text-center pt-8 pb-6 px-4 sm:pt-12 sm:pb-8 sm:px-6 lg:pt-16 lg:pb-10 lg:px-12">
                        <!-- Brand Name -->
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-3 sm:mb-4 animate-fade-in animation-delay-200 animation-fill-both">
                            AMKAS
                        </h1>
                        
                        <!-- Tagline -->
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-300 font-medium mb-2 sm:mb-3 animate-fade-in animation-delay-400 animation-fill-both">
                            Kasir Modern untuk Bisnis Anda
                        </p>
                        <p class="text-xs sm:text-sm md:text-base text-slate-400 max-w-2xl mx-auto animate-fade-in animation-delay-600 animation-fill-both px-2">
                            Kelola bisnis Anda dengan sistem kasir yang cepat, mudah, dan terpercaya
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="px-4 sm:px-6 lg:px-12 pb-6 sm:pb-8">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
                            <!-- Feature 1 -->
                            <div class="group bg-slate-700/80 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-slate-600/50 hover:bg-slate-700/90 hover:border-slate-600/70 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-800 animation-fill-both text-center min-h-[140px] sm:min-h-[160px] lg:min-h-[200px] flex flex-col items-center justify-center">
                                <div class="text-4xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4 transform group-hover:scale-110 transition-transform duration-300">📦</div>
                                <h3 class="text-sm sm:text-base lg:text-lg font-bold text-white mb-1 sm:mb-2 leading-tight">Manajemen Barang</h3>
                                <p class="text-xs lg:text-sm text-white/70 leading-relaxed">Kelola stok dan harga dengan mudah</p>
                            </div>

                            <!-- Feature 2 -->
                            <div class="group bg-slate-700/80 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-slate-600/50 hover:bg-slate-700/90 hover:border-slate-600/70 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-900 animation-fill-both text-center min-h-[140px] sm:min-h-[160px] lg:min-h-[200px] flex flex-col items-center justify-center">
                                <div class="text-4xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4 transform group-hover:scale-110 transition-transform duration-300">⚡</div>
                                <h3 class="text-sm sm:text-base lg:text-lg font-bold text-white mb-1 sm:mb-2 leading-tight">Transaksi Cepat</h3>
                                <p class="text-xs lg:text-sm text-white/70 leading-relaxed">Proses penjualan secara efisien</p>
                            </div>

                            <!-- Feature 3 -->
                            <div class="group bg-slate-700/80 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-slate-600/50 hover:bg-slate-700/90 hover:border-slate-600/70 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-1000 animation-fill-both text-center min-h-[140px] sm:min-h-[160px] lg:min-h-[200px] flex flex-col items-center justify-center">
                                <div class="text-4xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4 transform group-hover:scale-110 transition-transform duration-300">👥</div>
                                <h3 class="text-sm sm:text-base lg:text-lg font-bold text-white mb-1 sm:mb-2 leading-tight">Multi User</h3>
                                <p class="text-xs lg:text-sm text-white/70 leading-relaxed">Akses sesuai peran pengguna</p>
                            </div>

                            <!-- Feature 4 -->
                            <div class="group bg-slate-700/80 rounded-xl sm:rounded-2xl p-4 sm:p-5 lg:p-6 border border-slate-600/50 hover:bg-slate-700/90 hover:border-slate-600/70 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-800/20 animate-fade-in-up animation-delay-1100 animation-fill-both text-center min-h-[140px] sm:min-h-[160px] lg:min-h-[200px] flex flex-col items-center justify-center">
                                <div class="text-4xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4 transform group-hover:scale-110 transition-transform duration-300">📊</div>
                                <h3 class="text-sm sm:text-base lg:text-lg font-bold text-white mb-1 sm:mb-2 leading-tight">Laporan Lengkap</h3>
                                <p class="text-xs lg:text-sm text-white/70 leading-relaxed">Pantau performa bisnis real-time</p>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-stretch sm:items-center pb-6 sm:pb-8 animate-fade-in animation-delay-1200 animation-fill-both">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 text-base sm:text-lg font-semibold text-white bg-slate-700/90 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-slate-800/50 transition-all duration-300 hover:-translate-y-1 w-full sm:w-auto hover:bg-slate-700">
                                        <span class="relative z-10 flex items-center justify-center gap-2">
                                            Dashboard
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </span>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 text-base sm:text-lg font-semibold text-white bg-slate-700/90 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-slate-800/50 transition-all duration-300 hover:-translate-y-1 w-full sm:w-auto hover:bg-slate-700">
                                        <span class="relative z-10 flex items-center justify-center gap-2">
                                            Masuk
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </span>
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="text-center mt-6 sm:mt-8 text-slate-400 text-xs sm:text-sm animate-fade-in animation-delay-1400 animation-fill-both px-4">
                    <p>&copy; 2025 AMKAS. Sistem Kasir Modern.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>