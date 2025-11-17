<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\Barang;
use App\Exports\LaporanPenjualanExport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanPenjualanExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_creates_multiple_sheets()
    {
        $customer = Customer::factory()->create();
        $barang = Barang::factory()->create();

        // Create transaction in current month
        Transaksi::factory()->create([
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'status' => 'selesai',
            'total_harga' => 50000,
            'tanggal_pembelian' => now(),
            'tipe_pembayaran' => 'tunai',
            'alamat_pengantaran' => 'Test Address',
            'harga_barang' => 10000,
            'jumlah' => 5,
        ]);

        $export = new LaporanPenjualanExport();
        $sheets = $export->sheets();

        $this->assertNotEmpty($sheets);
        $this->assertInstanceOf(\App\Exports\LaporanPenjualanPerBulanSheet::class, $sheets[0]);
    }

    public function test_sheet_returns_correct_data()
    {
        $customer = Customer::factory()->create();
        $barang = Barang::factory()->create();

        $customer->update(['alamat' => 'Test Address']);

        $completedTransaction = Transaksi::factory()->create([
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'status' => 'selesai',
            'total_harga' => 50000,
            'tanggal_pembelian' => now(),
            'tipe_pembayaran' => 'tunai',
            'alamat_pengantaran' => 'Test Address',
            'harga_barang' => 10000,
            'jumlah' => 5,
        ]);

        Transaksi::factory()->create(['status' => 'batal']);

        $sheet = new \App\Exports\LaporanPenjualanPerBulanSheet(now()->year, now()->month);
        $collection = $sheet->collection();

        $this->assertCount(1, $collection);
        $order = $collection->first();
        $this->assertEquals($completedTransaction->order_id, $order->order_id);
        $this->assertEquals($customer->nama_customer, $order->customer->nama_customer);
        $this->assertEquals($barang->nama_barang, $order->items->first()->barang->nama_barang);
        $this->assertEquals($completedTransaction->jumlah, $order->total_jumlah);
        $this->assertEquals($completedTransaction->total_harga, $order->total_harga);
        $this->assertEquals(now()->format('d-m-Y'), $order->tanggal_pembelian->format('d-m-Y'));
        $this->assertEquals('tunai', $order->tipe_pembayaran);
        $this->assertEquals('Test Address', $order->customer->alamat);
    }

    public function test_sheet_headings_returns_correct_headers()
    {
        $sheet = new \App\Exports\LaporanPenjualanPerBulanSheet(now()->year, now()->month);
        $headings = $sheet->headings();

        $expectedHeadings = [
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
            'Status',
        ];

        $this->assertEquals($expectedHeadings, $headings);
    }

    public function test_sheet_title_returns_correct_format()
    {
        $sheet = new \App\Exports\LaporanPenjualanPerBulanSheet(2023, 10);
        $title = $sheet->title();

        $this->assertEquals('Oct 2023', $title);
    }

    public function test_map_formats_data_correctly()
    {
        $customer = Customer::factory()->create(['nama_customer' => 'John Doe', 'tipe_pembeli' => 'pembeli', 'alamat' => 'Test Address']);
        $barang = Barang::factory()->create(['nama_barang' => 'Test Product']);

        $transaction = Transaksi::factory()->create([
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'status' => 'selesai',
            'total_harga' => 50000,
            'tanggal_pembelian' => now(),
            'tipe_pembayaran' => 'tunai',
            'alamat_pengantaran' => 'Test Address',
            'harga_barang' => 10000,
            'jumlah' => 5,
        ]);

        // Create order-like object for mapping
        $order = (object) [
            'order_id' => $transaction->order_id,
            'customer' => $customer,
            'items' => collect([$transaction]),
            'total_jumlah' => 5,
            'total_harga' => 50000,
            'tanggal_pembelian' => $transaction->tanggal_pembelian,
            'tipe_pembayaran' => 'tunai',
        ];

        $sheet = new \App\Exports\LaporanPenjualanPerBulanSheet(now()->year, now()->month);
        $mapped = $sheet->map($order);

        $expected = [
            $transaction->order_id,
            'John Doe',
            'pembeli',
            'Test Product (5)',
            5,
            50000,
            50000,
            now()->format('d-m-Y'),
            'Tunai',
            'Test Address',
            'Selesai',
        ];

        $this->assertEquals($expected, $mapped);
    }
}
