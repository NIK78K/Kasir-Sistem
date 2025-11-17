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
     * Mengambil data transaksi yang sudah selesai untuk bulan tertentu, dikelompokkan per order
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Group transactions by order_id and get aggregated data
        $orders = Transaksi::where('status', 'selesai')
            ->whereYear('tanggal_pembelian', $this->year)
            ->whereMonth('tanggal_pembelian', $this->month)
            ->selectRaw('
                order_id,
                customer_id,
                MIN(tanggal_pembelian) as tanggal_pembelian,
                tipe_pembayaran,
                SUM(jumlah) as total_jumlah,
                SUM(total_harga) as total_harga,
                uang_dibayar,
                kembalian
            ')
            ->groupBy('order_id', 'customer_id', 'tipe_pembayaran', 'uang_dibayar', 'kembalian')
            ->orderBy('tanggal_pembelian', 'desc')
            ->get();

        // Get detailed items for each order
        $orders->transform(function ($order) {
            $order->items = Transaksi::with('barang', 'customer')
                ->where('order_id', $order->order_id)
                ->where('status', 'selesai')
                ->get();
            $order->customer = $order->items->first()->customer;
            return $order;
        });

        return $orders;
    }

    /**
     * Mapping data untuk setiap row
     *
     * @param mixed $order
     * @return array
     */
    public function map($order): array
    {
        // Combine all items in the order
        $barangList = $order->items->map(function($item) {
            if ($item->barang) {
                return $item->barang->nama_barang . ' (' . $item->jumlah . ')';
            } else {
                return 'Barang tidak ditemukan (' . $item->jumlah . ')';
            }
        })->join(', ');

        return [
            $order->order_id,
            $order->customer->nama_customer ?? '-',
            $order->customer->tipe_pembeli ?? '-',
            $barangList,
            $order->total_jumlah,
            $order->total_harga, // Total harga per order
            $order->total_harga, // Total harga (sama dengan sebelumnya untuk konsistensi)
            $order->tanggal_pembelian->format('d-m-Y'),
            $this->formatTipePembayaran($order->tipe_pembayaran),
            'Selesai',
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
            'Status',
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
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Jumlah
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