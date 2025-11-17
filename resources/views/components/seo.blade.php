@php
    // Accept passed props (Laravel automatically extracts public attributes)
    $baseDescription = 'AMKAS adalah sistem kasir modern untuk mengelola penjualan, pelanggan, dan stok secara cepat dan efisien.';
    // If no description passed, try simple route-based defaults
    $routeName = optional(request()->route())->getName();
    $autoDescriptions = [
        'dashboard' => 'Ringkasan penjualan, transaksi, dan performa bisnis Anda di AMKAS.',
        'barang.index' => 'Daftar produk lengkap dengan harga, stok, dan kategori di AMKAS.',
        'customer.index' => 'Kelola data pelanggan, pencarian, dan detail kontak di AMKAS.',
        'owner.dataCustomer' => 'Data customer lengkap untuk pemilik: kelola dan analisis pelanggan di AMKAS.',
        'user.index' => 'Kelola akun user: tambah, edit, dan hapus pengguna sistem AMKAS.',
        'transaksi.index' => 'Halaman transaksi: pilih customer, cari produk, dan proses pembayaran di AMKAS.',
        'transaksi.confirm' => 'Konfirmasi pesanan: ringkas detail barang, total, dan pembayaran sebelum final di AMKAS.',
        'transaksi.listReturnable' => 'Daftar transaksi yang dapat direturn oleh customer di AMKAS.',
        'transaksi.barangReturn' => 'Form pengembalian barang untuk transaksi tertentu di AMKAS.',
        'transaksi.return' => 'Proses return barang: pilih item dan alasan pengembalian di AMKAS.',
        'owner.laporanPenjualan' => 'Laporan penjualan selesai: telusuri transaksi, filter tanggal & metode pembayaran di AMKAS.',
        'owner.laporanBarangReturn' => 'Laporan return barang: analisis barang yang direturn oleh customer di AMKAS.',
        'profile.edit' => 'Kelola profil akun Anda di AMKAS.',
        // Auth related
        'login' => 'Masuk ke sistem kasir AMKAS untuk mengelola penjualan dan data.',
        'password.request' => 'Form lupa password untuk memulihkan akses akun AMKAS.',
        'password.reset' => 'Atur ulang password akun Anda di AMKAS.',
        'verification.notice' => 'Verifikasi email akun AMKAS untuk mengaktifkan fitur penuh.',
    ];
    $seoDescription = trim($description ?: ($autoDescriptions[$routeName] ?? $baseDescription));
    // Fallback title logic
    $seoTitle = trim($title ?: config('app.name', 'Laravel'));
    // Ensure canonical has no trailing slash duplicates
    // Canonical without query string
    $canonical = rtrim(url()->current(), '/');
@endphp

<!-- SEO Component -->
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ $canonical }}" />

<!-- Open Graph -->
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $seoTitle }}" />
<meta property="og:description" content="{{ $seoDescription }}" />
<meta property="og:url" content="{{ $canonical }}" />
<meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}" />

<!-- Twitter -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $seoTitle }}" />
<meta name="twitter:description" content="{{ $seoDescription }}" />
