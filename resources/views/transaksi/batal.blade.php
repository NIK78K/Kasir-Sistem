@extends('layouts.app')

@section('title', 'Transaksi Batal')

@section('content')

    @if(session('success'))
        <div style="color:green">{{ session('success') }}</div>
    @endif

    <h3>Daftar Transaksi Batal</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="margin-top:10px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pembeli</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Barang</th>
                <th>Diskon (%)</th>
                <th>Total Harga</th>
                <th>Tanggal Pembelian</th>
                <th>Tipe Pembayaran</th>
                <th>Alamat Pengantaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
                <tr>
                    <td>{{ $transaksi->id }}</td>
                    <td>{{ $transaksi->customer->nama_customer }}</td>
                    <td>{{ $transaksi->barang->nama_barang }}</td>
                    <td>{{ $transaksi->jumlah }}</td>
                    <td>Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->diskon }}</td>
                    <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->tanggal_pembelian->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($transaksi->tipe_pembayaran) }}</td>
                    <td>{{ $transaksi->alamat_pengantaran }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $transaksis->links() }}
@endsection