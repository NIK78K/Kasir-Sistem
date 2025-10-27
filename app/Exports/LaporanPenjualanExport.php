<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class LaporanPenjualanExport implements WithMultipleSheets
{
    /**
     * Mengembalikan array of sheets untuk setiap bulan
     *
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Ambil 12 bulan terakhir
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;

            // Cek apakah ada data untuk bulan ini
            $hasData = Transaksi::whereYear('tanggal_pembelian', $year)
                ->whereMonth('tanggal_pembelian', $month)
                ->where('status', 'selesai')
                ->exists();

            if ($hasData) {
                $sheets[] = new LaporanPenjualanPerBulanSheet($year, $month);
            }
        }

        // Jika tidak ada data di 12 bulan terakhir, buat sheet kosong untuk bulan current
        if (empty($sheets)) {
            $currentDate = Carbon::now();
            $sheets[] = new LaporanPenjualanPerBulanSheet($currentDate->year, $currentDate->month);
        }

        return $sheets;
    }
}

class LaporanPenjualanPerBulanSheet implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{
    private $year;
    private $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Nama sheet berdasarkan bulan dan tahun
     *
     * @return string
     */
    public function title(): string
    {
        return Carbon::createFromDate($this->year, $this->month, 1)->format('M Y');
    }
    /**
     * Mengambil data transaksi yang sudah selesai untuk bulan tertentu
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Transaksi::with(['barang', 'customer'])
            ->where('status', 'selesai')
            ->whereYear('tanggal_pembelian', $this->year)
            ->whereMonth('tanggal_pembelian', $this->month)
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
            $transaksi->customer->tipe_pembeli ?? '-',
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
            'Tipe Pembeli',
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
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Harga Barang
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Total Harga
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