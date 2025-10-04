@extends('layouts.app')

@section('title', 'Transaksi Batal')

@section('content')

    @if(session('success'))
        <div style="color:green">{{ session('success') }}</div>
    @endif

    @if(isset($transaksi))
        <div style="border: 1px solid black; border-radius: 10px; padding: 20px; max-width: 600px; margin: auto;">
            <a href="{{ route('transaksi.listBatal') }}" style="text-decoration:none; font-weight:bold;">&larr; Kembali</a>
            <h3 style="text-align:center; margin-bottom: 20px;">Detail Transaksi</h3>
            <div>
                <strong>Nomor Transaksi</strong> : {{ str_pad($transaksi->id, 9, '0', STR_PAD_LEFT) }}
            </div>
            <div>
                <strong>Nama Customer</strong> : {{ $transaksi->customer->nama_customer }}
            </div>
            <div>
                <strong>Tipe Customer</strong> : {{ $transaksi->customer->tipe_customer ?? 'N/A' }}
            </div>
            <div style="margin-top: 20px;">
                <strong>Daftar Belanja</strong>
                <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left; border-bottom: 1px solid black;">Nama Barang</th>
                            <th style="text-align:center; border-bottom: 1px solid black;">Jumlah</th>
                            <th style="text-align:right; border-bottom: 1px solid black;">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight:bold;">{{ $transaksi->barang->nama_barang }}</td>
                            <td style="text-align:center;">{{ $transaksi->jumlah }}</td>
                            <td style="text-align:right;">Rp {{ number_format($transaksi->harga_barang, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form method="POST" action="{{ route('transaksi.batal') }}" style="margin-top: 30px; text-align:center;">
                @csrf
                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                <label style="display: block; margin-bottom: 10px;">
                    <input type="checkbox" name="confirm_batal" value="1" required>
                    Konfirmasi pembatalan transaksi
                </label>
                <button type="submit" style="padding: 10px 20px; border-radius: 20px; border: 1px solid black; background-color: white; cursor: pointer;">
                    Batalkan Transaksi
                </button>
            </form>
        </div>
    @elseif(isset($transaksis))
        <h3>Daftar Transaksi yang Dapat Dibatalkan</h3>
        <table border="1" cellpadding="8" cellspacing="0" style="margin-top:10px; width: 100%;">
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
                    <th>Aksi</th>
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
                        <td><a href="{{ route('transaksi.listBatal', ['id' => $transaksi->id]) }}">Batalkan</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $transaksis->links() }}
    @endif

@endsection
