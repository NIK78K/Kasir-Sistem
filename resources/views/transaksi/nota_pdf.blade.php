<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian</title>
</head>
<body class="font-['Arial'] m-5">
    <center>
        <div class="max-w-4xl">
            <h1 class="text-center">Nota Pembelian</h1>

            <h2 class="text-left">Detail Customer</h2>
            <div class="text-left">
                <p><strong>Nama:</strong> {{ $customer->nama_customer ?? '-' }}</p>
                <p><strong>No HP:</strong> {{ $customer->no_hp ?? '-' }}</p>
                <p><strong>Tipe Customer:</strong> {{ $customer->tipe_pembeli ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $customer->alamat ?? '-' }}</p>
            </div>

            <h2 class="text-left">Detail Pesanan</h2>
            <table class="w-full border-collapse border border-gray-800 mt-5">
                <thead>
                    <tr>
                        <th class="border border-gray-800 p-2 text-left">Barang</th>
                        <th class="border border-gray-800 p-2 text-left">Jumlah</th>
                        <th class="border border-gray-800 p-2 text-left">Harga Satuan</th>
                        <th class="border border-gray-800 p-2 text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $transaksi)
                        <tr>
                            <td class="border border-gray-800 p-2">{{ $transaksi->barang->nama_barang }}</td>
                            <td class="border border-gray-800 p-2">{{ $transaksi->jumlah }}</td>
                            <td class="border border-gray-800 p-2">Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                            <td class="border border-gray-800 p-2">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="text-right font-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>

            <p class="text-left">Terima kasih atas pembelian Anda!</p>
        </div>
    </center>
</body>
</html>
