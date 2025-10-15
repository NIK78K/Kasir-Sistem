@extends('layouts.app')

@section('title', '- Dashboard')

@section('content')
<style>
    .dashboard-welcome {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 40px 32px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        margin-bottom: 24px;
    }
    
    .welcome-title {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .welcome-text {
        font-size: 16px;
        opacity: 0.95;
        line-height: 1.6;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 12px;
        padding: 24px;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
    }
    
    .stat-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }
    
    .stat-title {
        font-size: 14px;
        color: #666;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .quick-menu {
        background: linear-gradient(135deg, #e0e7ff 0%, #cffafe 100%);
        border-radius: 12px;
        padding: 24px;
    }
    
    .menu-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 16px;
    }
    
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
    }
    
    .menu-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px;
        background: white;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .menu-item:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .menu-icon {
        font-size: 28px;
    }
    
    @media (max-width: 768px) {
        .dashboard-welcome {
            padding: 24px 20px;
        }
        
        .welcome-title {
            font-size: 22px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-welcome">
    <div class="welcome-title">Selamat Datang, {{ Auth::user()->name }}! 👋</div>
    <div class="welcome-text">
        Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>. 
        Kelola bisnis Anda dengan mudah melalui sistem AMKas.
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
    <div class="stat-icon">📦</div>
    <div class="stat-title">Total Produk</div>
    <div class="stat-value">{{ $totalProduk }}</div>
</div>
    
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-title">Penjualan Hari Ini</div>
    <div class="stat-value">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</div>
    </div>
    
    <div class="stat-card">
    <div class="stat-icon">📊</div>
    <div class="stat-title">Total Transaksi</div>
    <div class="stat-value">{{ $totalTransaksi }}</div>
</div>
    
    <div class="stat-card">
    <div class="stat-icon">👥</div>
    <div class="stat-title">Total Customer</div>
    <div class="stat-value">{{ $totalCustomer }}</div>
</div>
</div>

<div class="quick-menu">
    <h3 class="menu-title">Menu Cepat</h3>
    <div class="menu-grid">
        @if(Auth::user()->role === 'kasir')
            <a href="{{ route('transaksi.index') }}" class="menu-item">
                <span class="menu-icon">🛒</span>
                <span>Transaksi</span>
            </a>
            <a href="{{ route('barang.index') }}" class="menu-item">
                <span class="menu-icon">📦</span>
                <span>Data Barang</span>
            </a>
            <a href="{{ route('customer.index') }}" class="menu-item">
                <span class="menu-icon">👥</span>
                <span>Data Customer</span>
            </a>
            <a href="{{ route('transaksi.listReturnable') }}" class="menu-item">
                <span class="menu-icon">↩️</span>
                <span>Barang Return</span>
            </a>
        @elseif(Auth::user()->role === 'owner')
            <a href="{{ url('data-barang') }}" class="menu-item">
                <span class="menu-icon">📦</span>
                <span>Data Barang</span>
            </a>
            <a href="{{ url('data-customer') }}" class="menu-item">
                <span class="menu-icon">👥</span>
                <span>Data Customer</span>
            </a>
            <a href="{{ route('user.index') }}" class="menu-item">
                <span class="menu-icon">👤</span>
                <span>Data User</span>
            </a>
            <a href="{{ route('owner.laporanPenjualan') }}" class="menu-item">
                <span class="menu-icon">📈</span>
                <span>Laporan Penjualan</span>
            </a>
            <a href="{{ route('owner.laporanBarangReturn') }}" class="menu-item">
                <span class="menu-icon">📋</span>
                <span>Laporan Return</span>
            </a>
        @endif
    </div>
</div>
@endsection
