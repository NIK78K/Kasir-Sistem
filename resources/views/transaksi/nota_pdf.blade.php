<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Nota Pembelian</h1>

    <h2>Detail Customer</h2>
    <p><strong>Nama:</strong> {{ $customer->nama_customer ?? '-' }}</p>
    <p><strong>No HP:</strong> {{ $customer->no_hp ?? '-' }}</p>
    <p><strong>Tipe Customer:</strong> {{ $customer->tipe_pembeli ?? '-' }}</p>
    <p><strong>Alamat:</strong> {{ $customer->alamat ?? '-' }}</p>

    <h2>Detail Pesanan</h2>
    <table>
        <thead>
            <tr>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Diskon</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
                <tr>
                    <td>{{ $transaksi->barang->nama_barang }}</td>
                    <td>{{ $transaksi->jumlah }}</td>
                    <td>Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->diskon }}%</td>
                    <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>

    <p>Terima kasih atas pembelian Anda!</p>
</body>
</html>
