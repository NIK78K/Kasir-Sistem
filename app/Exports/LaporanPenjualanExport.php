<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanPenjualanExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping,
    WithColumnFormatting,
    WithStyles,
    ShouldAutoSize
{
    /**
     * Mengambil data transaksi yang sudah selesai
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Transaksi::with(['barang', 'customer'])
            ->where('status', 'selesai')
            ->orderBy('tanggal_pembelian', 'desc')
            ->get();
    }

    /**
     * Mapping data untuk setiap row
     *
     * @param Transaksi $transaksi
     * @return array
     */
    public function map($transaksi): array
    {
        return [
            $transaksi->id,
            $transaksi->customer->nama_customer ?? '-',
            $transaksi->barang->nama_barang ?? '-',
            $transaksi->jumlah,
            $transaksi->harga_barang,
            $transaksi->total_harga,
            $transaksi->tanggal_pembelian->format('d-m-Y'),
            $this->formatTipePembayaran($transaksi->tipe_pembayaran),
            $transaksi->alamat_pengantaran ?? '-',
        ];
    }

    /**
     * Header kolom untuk Excel
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Pembeli',
            'Nama Barang',
            'Jumlah',
            'Harga Barang',
            'Total Harga',
            'Tanggal Pembelian',
            'Tipe Pembayaran',
            'Alamat Pengantaran',
        ];
    }

    /**
     * Format kolom angka sebagai currency
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Harga Barang
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Total Harga
        ];
    }

    /**
     * Styling untuk worksheet
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Format tipe pembayaran
     *
     * @param string|null $tipe
     * @return string
     */
    private function formatTipePembayaran(?string $tipe): string
    {
        if (!$tipe) {
            return '-';
        }

        return match(strtolower($tipe)) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'e_wallet' => 'E-Wallet',
            default => ucfirst($tipe)
        };
    }
}