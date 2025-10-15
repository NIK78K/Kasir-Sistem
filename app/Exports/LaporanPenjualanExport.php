<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPenjualanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Transaksi::with('barang', 'customer')
            ->where('status', 'selesai')
            ->get()
            ->map(function ($transaksi) {
                return [
                    'ID' => $transaksi->id,
                    'Nama Pembeli' => $transaksi->customer->nama_customer ?? '',
                    'Nama Barang' => $transaksi->barang->nama_barang ?? '',
                    'Jumlah' => $transaksi->jumlah,
                    'Harga Barang' => $transaksi->harga_barang,
                    'Diskon (%)' => $transaksi->diskon,
                    'Total Harga' => $transaksi->total_harga,
                    'Tanggal Pembelian' => $transaksi->tanggal_pembelian->format('d-m-Y'),
                    'Tipe Pembayaran' => ucfirst($transaksi->tipe_pembayaran),
                    'Alamat Pengantaran' => $transaksi->alamat_pengantaran,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pembeli',
            'Nama Barang',
            'Jumlah',
            'Harga Barang',
            'Diskon (%)',
            'Total Harga',
            'Tanggal Pembelian',
            'Tipe Pembayaran',
            'Alamat Pengantaran',
        ];
    }
}
