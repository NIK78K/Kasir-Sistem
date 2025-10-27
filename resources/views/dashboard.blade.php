@extends('layouts.app')

@section('title', '- Dashboard')

@section('content')

<!-- Welcome Banner dengan Gradient Dinamis -->
<div class="relative rounded-3xl p-3 md:p-4 text-white shadow-2xl mb-6 overflow-hidden bg-gray-700">
    <div class="absolute top-0 right-0 w-48 h-48 bg-white opacity-10 rounded-full -mr-24 -mt-24 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-yellow-300 opacity-10 rounded-full -ml-36 -mb-36 blur-3xl"></div>

    <div class="relative z-10">
        <div class="flex items-center gap-2 mb-2">
            <div class="text-3xl animate-bounce">👋</div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-1 tracking-tight">
                    Halo, {{ Auth::user()->name }}!
                </h1>
                <div class="flex items-center gap-2 text-sm md:text-base opacity-95">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full font-semibold border border-white/30">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                    <span class="hidden md:inline">• Kelola bisnis Anda dengan mudah melalui AMKas</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards dengan Animasi -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ Auth::user()->role === 'owner' ? '5' : '4' }} gap-4 mb-6">
    <!-- Card 1: Total Produk -->
    <div class="group relative bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 opacity-10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl">📦</span>
                </div>
                <div class="p-1.5 bg-blue-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wide">Total Produk</div>
            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                {{ $totalProduk }}
            </div>
        </div>
    </div>

    <!-- Card 2: Penjualan Hari Ini -->
    <div class="group relative bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 opacity-10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="p-1.5 bg-green-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wide">Penjualan Hari Ini</div>
            <div class="text-xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">
                Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'owner')
    <!-- Card 3: Penjualan Bulan Ini -->
    <div class="group relative bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-teal-400 to-teal-600 opacity-10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl">📈</span>
                </div>
                <div class="p-1.5 bg-teal-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wide">Penjualan Bulan Ini</div>
            <div class="text-xl font-bold bg-gradient-to-r from-teal-600 to-teal-800 bg-clip-text text-transparent">
                Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}
            </div>
        </div>
    </div>
    @endif

    <!-- Card 4: Total Transaksi -->
    <div class="group relative bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-400 to-purple-600 opacity-10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl">📊</span>
                </div>
                <div class="p-1.5 bg-purple-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wide">Total Transaksi</div>
            <div class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">
                {{ $totalTransaksi }}
            </div>
        </div>
    </div>

    <!-- Card 5: Total Customer -->
    <div class="group relative bg-white rounded-xl p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-400 to-orange-600 opacity-10 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl">👥</span>
                </div>
                <div class="p-1.5 bg-orange-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-gray-500 font-semibold mb-1 uppercase tracking-wide">Total Customer</div>
            <div class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-orange-800 bg-clip-text text-transparent">
                {{ $totalCustomer }}
            </div>
        </div>
    </div>
</div>

<!-- Menu Cepat dengan Design Modern -->
<div class="flex items-center justify-between mb-4">
    <div>
        <h3 class="text-xl font-bold text-gray-800 mb-1">Menu Cepat</h3>
        <p class="text-gray-500 text-sm">Akses fitur dengan cepat dan mudah</p>
    </div>
    <div class="hidden md:block p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
    </div>
</div>

<!-- Grid untuk Kasir: 4 kolom (lebih pas untuk 4 item) -->
@if(Auth::user()->role === 'kasir')
<div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    <a href="{{ route('transaksi.index') }}" class="group relative bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-indigo-600 hover:to-purple-600 border border-indigo-200 hover:border-transparent overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10 flex flex-col items-center gap-2">
            <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">🛒</div>
            <span class="text-xs font-bold text-indigo-900 group-hover:text-white transition-colors duration-300">Transaksi</span>
        </div>
    </a>

    <a href="{{ route('barang.index') }}" class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-blue-600 hover:to-blue-700 border border-blue-200 hover:border-transparent overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10 flex flex-col items-center gap-2">
            <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">📦</div>
            <span class="text-xs font-bold text-blue-900 group-hover:text-white transition-colors duration-300">Data Barang</span>
        </div>
    </a>

    <a href="{{ route('customer.index') }}" class="group relative bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-orange-600 hover:to-orange-700 border border-orange-200 hover:border-transparent overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-600 to-orange-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10 flex flex-col items-center gap-2">
            <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">👥</div>
            <span class="text-xs font-bold text-orange-900 group-hover:text-white transition-colors duration-300">Data Customer</span>
        </div>
    </a>

    <a href="{{ route('transaksi.listReturnable') }}" class="group relative bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-red-600 hover:to-red-700 border border-red-200 hover:border-transparent overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-red-600 to-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative z-10 flex flex-col items-center gap-2">
            <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">↩️</div>
            <span class="text-xs font-bold text-red-900 group-hover:text-white transition-colors duration-300">Barang Return</span>
        </div>
    </a>
</div>

<!-- Grid untuk Owner: 5 kolom (untuk 5 item) -->
@elseif(Auth::user()->role === 'owner')
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        <a href="{{ url('data-barang') }}" class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-blue-600 hover:to-blue-700 border border-blue-200 hover:border-transparent overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">📦</div>
                <span class="text-xs font-bold text-blue-900 group-hover:text-white transition-colors duration-300">Data Barang</span>
            </div>
        </a>

        <a href="{{ url('data-customer') }}" class="group relative bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-orange-600 hover:to-orange-700 border border-orange-200 hover:border-transparent overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-600 to-orange-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">👥</div>
                <span class="text-xs font-bold text-orange-900 group-hover:text-white transition-colors duration-300">Data Customer</span>
            </div>
        </a>

        <a href="{{ route('user.index') }}" class="group relative bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-purple-600 hover:to-purple-700 border border-purple-200 hover:border-transparent overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">👤</div>
                <span class="text-xs font-bold text-purple-900 group-hover:text-white transition-colors duration-300">Data User</span>
            </div>
        </a>

        <a href="{{ route('owner.laporanPenjualan') }}" class="group relative bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-green-600 hover:to-green-700 border border-green-200 hover:border-transparent overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-green-600 to-green-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">📈</div>
                <span class="text-xs font-bold text-green-900 group-hover:text-white transition-colors duration-300">Laporan Penjualan</span>
            </div>
        </a>

        <a href="{{ route('owner.laporanBarangReturn') }}" class="group relative bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:from-indigo-600 hover:to-indigo-700 border border-indigo-200 hover:border-transparent overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="text-3xl transform group-hover:scale-110 transition-transform duration-300">📋</div>
                <span class="text-xs font-bold text-indigo-900 group-hover:text-white transition-colors duration-300">Laporan Return</span>
            </div>
        </a>
</div>
@endif

@if(Auth::user()->role === 'owner')
<!-- Chart Section -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Chart Penjualan -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">Grafik Penjualan</h3>
                <p class="text-gray-500 text-sm">Data penjualan perbulan</p>
            </div>
            <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <div id="chart-penjualan" class="w-full"></div>
    </div>

    <!-- Chart Return -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-1">Grafik Barang Return</h3>
                <p class="text-gray-500 text-sm">Data barang return perbulan</p>
            </div>
            <div class="p-2 bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
        </div>
        <div id="chart-return" class="w-full"></div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chart data:', {
        penjualanPerBulan: @json($penjualanPerBulan),
        returnPerBulan: @json($returnPerBulan),
        categories: @json($categories)
    });

    // Check if ApexCharts is available
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts is not loaded');
        return;
    }

    // Chart Penjualan
    var optionsPenjualan = {
        series: [{
            name: 'Penjualan',
            data: @json($penjualanPerBulan)
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            type: 'category',
            categories: @json($categories)
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    if (value >= 1000000) {
                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                    } else if (value >= 1000) {
                        return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                    }
                    return 'Rp ' + value;
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return 'Rp ' + value.toLocaleString('id-ID');
                }
            }
        },
        colors: ['#3B82F6'], // Blue for sales
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                opacityFrom: 0.4,
                opacityTo: 0.1,
            }
        }
    };

    var chartPenjualanElement = document.querySelector("#chart-penjualan");
    if (chartPenjualanElement) {
        console.log('Chart penjualan element found, initializing chart...');
        try {
            var chartPenjualan = new ApexCharts(chartPenjualanElement, optionsPenjualan);
            chartPenjualan.render().then(function() {
                console.log('Chart penjualan rendered successfully');
            }).catch(function(err) {
                console.error('Chart penjualan render error:', err);
            });
        } catch (error) {
            console.error('Error creating chart penjualan:', error);
        }
    } else {
        console.error('Chart penjualan element not found');
    }

    // Chart Return
    var optionsReturn = {
        series: [{
            name: 'Barang Return',
            data: @json($returnPerBulan)
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: true
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            type: 'category',
            categories: @json($categories)
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    if (value >= 1000000) {
                        return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                    } else if (value >= 1000) {
                        return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                    }
                    return 'Rp ' + value;
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return 'Rp ' + value.toLocaleString('id-ID');
                }
            }
        },
        colors: ['#EF4444'], // Red for returns
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                opacityFrom: 0.4,
                opacityTo: 0.1,
            }
        }
    };

    var chartReturnElement = document.querySelector("#chart-return");
    if (chartReturnElement) {
        console.log('Chart return element found, initializing chart...');
        try {
            var chartReturn = new ApexCharts(chartReturnElement, optionsReturn);
            chartReturn.render().then(function() {
                console.log('Chart return rendered successfully');
            }).catch(function(err) {
                console.error('Chart return render error:', err);
            });
        } catch (error) {
            console.error('Error creating chart return:', error);
        }
    } else {
        console.error('Chart return element not found');
    }
});
</script>
@endpush
