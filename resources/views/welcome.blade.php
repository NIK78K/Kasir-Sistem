<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #333;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 60px 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo {
            font-size: 48px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }
        
        .tagline {
            font-size: 18px;
            color: #666;
            margin-bottom: 48px;
            font-weight: 400;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 48px;
        }
        
        .feature {
            background: #f8f9fa;
            padding: 24px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .feature:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
        }
        
        .feature-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }
        
        .feature-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .feature-desc {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }
        
        .cta-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        
        @media (max-width: 640px) {
            .container {
                padding: 40px 24px;
            }
            
            .logo {
                font-size: 36px;
            }
            
            .features {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .cta-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">AMKas</div>
        <div class="tagline">Point of Sale Modern untuk Bisnis Anda</div>
        
        <div class="features">
            <div class="feature">
                <div class="feature-icon">📦</div>
                <div class="feature-title">Manajemen Barang</div>
                <div class="feature-desc">Kelola stok dan harga dengan mudah</div>
            </div>
            
            <div class="feature">
                <div class="feature-icon">⚡</div>
                <div class="feature-title">Transaksi Cepat</div>
                <div class="feature-desc">Proses penjualan secara efisien</div>
            </div>
            
            <div class="feature">
                <div class="feature-icon">👥</div>
                <div class="feature-title">Multi User</div>
                <div class="feature-desc">Akses sesuai peran pengguna</div>
            </div>
            
            <div class="feature">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Laporan Lengkap</div>
                <div class="feature-desc">Pantau performa bisnis real-time</div>
            </div>
        </div>
        
        <div class="cta-buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-secondary">Daftar</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</body>
</html>