@extends('layouts.app')

@section('title', '- Dashboard')

@section('content')

<div class="bg-gradient-to-br from-gray-600 to-gray-800 rounded-2xl p-10 text-white shadow-xl mb-6">
    <div class="text-3xl font-semibold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</div>
    <div class="text-lg opacity-95 leading-relaxed">
        Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>.
        Kelola bisnis Anda dengan mudah melalui sistem AMKas.
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-gray-200 text-center">
        <div class="text-5xl mb-3">📦</div>
        <div class="text-sm text-gray-600 font-medium mb-2">Total Produk</div>
        <div class="text-4xl font-bold bg-gradient-to-r from-gray-600 to-gray-800 bg-clip-text text-transparent">{{ $totalProduk }}</div>
    </div>

    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-gray-200 text-center">
        <div class="text-5xl mb-3">💰</div>
        <div class="text-sm text-gray-600 font-medium mb-2">Penjualan Hari Ini</div>
        <div class="text-4xl font-bold bg-gradient-to-r from-gray-600 to-gray-800 bg-clip-text text-transparent">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</div>
    </div>

    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-gray-200 text-center">
        <div class="text-5xl mb-3">📊</div>
        <div class="text-sm text-gray-600 font-medium mb-2">Total Transaksi</div>
        <div class="text-4xl font-bold bg-gradient-to-r from-gray-600 to-gray-800 bg-clip-text text-transparent">{{ $totalTransaksi }}</div>
    </div>

    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl p-6 transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-gray-200 text-center">
        <div class="text-5xl mb-3">👥</div>
        <div class="text-sm text-gray-600 font-medium mb-2">Total Customer</div>
        <div class="text-4xl font-bold bg-gradient-to-r from-gray-600 to-gray-800 bg-clip-text text-transparent">{{ $totalCustomer }}</div>
    </div>
</div>

<div class="bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl p-6">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Menu Cepat</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
        @if(Auth::user()->role === 'kasir')
            <a href="{{ route('transaksi.index') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">🛒</span>
                <span>Transaksi</span>
            </a>
            <a href="{{ route('barang.index') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">📦</span>
                <span>Data Barang</span>
            </a>
            <a href="{{ route('customer.index') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">👥</span>
                <span>Data Customer</span>
            </a>
            <a href="{{ route('transaksi.listReturnable') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">↩️</span>
                <span>Barang Return</span>
            </a>
        @elseif(Auth::user()->role === 'owner')
            <a href="{{ url('data-barang') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">📦</span>
                <span>Data Barang</span>
            </a>
            <a href="{{ url('data-customer') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">👥</span>
                <span>Data Customer</span>
            </a>
            <a href="{{ route('user.index') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">👤</span>
                <span>Data User</span>
            </a>
            <a href="{{ route('owner.laporanPenjualan') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">📈</span>
                <span>Laporan Penjualan</span>
            </a>
            <a href="{{ route('owner.laporanBarangReturn') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl text-gray-800 font-medium transition-all hover:bg-gray-800 hover:text-white hover:-translate-y-0.5 hover:shadow-lg hover:shadow-gray-800/30 text-sm">
                <span class="text-3xl">📋</span>
                <span>Laporan Return</span>
            </a>
        @endif
    </div>
</div>
@endsection
