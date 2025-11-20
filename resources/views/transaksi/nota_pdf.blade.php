<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 15px;">
    
    <!-- Header -->
    <div style="text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px;">
        <h1 style="margin: 0; font-size: 24px;">NOTA PEMBELIAN</h1>
        <p style="margin: 5px 0 0 0; font-size: 11px;">Terima kasih atas kepercayaan Anda</p>
        <p style="margin: 5px 0 0 0; font-size: 12px;">Nomor Transaksi: {{ $transaksis->first()->order_id }}</p>
        <p style="margin: 5px 0 0 0; font-size: 12px;">Tanggal: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Detail Customer -->
    <div style="margin-bottom: 15px;">
        <h3 style="background-color: #333; color: white; padding: 5px 10px; margin: 0 0 8px 0; font-size: 14px;">Detail Customer</h3>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="padding: 3px 0; width: 120px;">Nama</td>
                <td style="padding: 3px 0;">: {{ $customer->nama_customer ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">No HP</td>
                <td style="padding: 3px 0;">: {{ $customer->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0;">Tipe Customer</td>
                <td style="padding: 3px 0;">: {{ $customer->tipe_pembeli ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 3px 0; vertical-align: top;">Alamat</td>
                <td style="padding: 3px 0;">: {{ $customer->alamat ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Detail Pesanan -->
    <div style="margin-bottom: 15px;">
        <h3 style="background-color: #333; color: white; padding: 5px 10px; margin: 0 0 8px 0; font-size: 14px;">Detail Pesanan</h3>
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: left;">Barang</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 60px;">Jumlah</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: right; width: 100px;">Harga Satuan</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: right; width: 100px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $transaksi)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 6px;">{{ $transaksi->barang->nama_barang }}</td>
                        <td style="border: 1px solid #ddd; padding: 6px; text-align: center;">{{ $transaksi->jumlah }}</td>
                        <td style="border: 1px solid #ddd; padding: 6px; text-align: right;">Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                        <td style="border: 1px solid #ddd; padding: 6px; text-align: right;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #f9f9f9;">
                    <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold;">TOTAL PEMBELIAN</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right; font-weight: bold; color: #d9534f;">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Detail Pembayaran -->
    <div style="margin-bottom: 15px;">
        <h3 style="background-color: #333; color: white; padding: 5px 10px; margin: 0 0 8px 0; font-size: 14px;">Detail Pembayaran</h3>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="padding: 4px 0; width: 120px;">Uang Dibayar</td>
                <td style="padding: 4px 0; text-align: right;">: Rp {{ number_format($transaksis->first()->uang_dibayar ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0;">Kembalian</td>
                <td style="padding: 4px 0; text-align: right; color: #5cb85c; font-weight: bold;">: Rp {{ number_format($transaksis->first()->kembalian ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 2px dashed #ccc;">
        <p style="margin: 0; font-size: 14px; font-weight: bold;">Terima Kasih Atas Pembelian Anda!</p>
        <p style="margin: 5px 0 0 0; font-size: 11px;">Belanja Kembali Ya :D</p>
    </div>

</body>
</html>