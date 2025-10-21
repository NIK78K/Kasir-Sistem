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

    public function test_collection_returns_completed_transactions()
    {
        $customer = Customer::factory()->create();
        $barang = Barang::factory()->create();

        $completedTransaction = Transaksi::factory()->create([
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'status' => 'selesai',
            'total_harga' => 50000,
            'tanggal_pembelian' => '2023-01-01',
            'tipe_pembayaran' => 'tunai',
            'alamat_pengantaran' => 'Test Address',
            'harga_barang' => 10000,
            'jumlah' => 5,
        ]);

        Transaksi::factory()->create(['status' => 'batal']);

        $export = new LaporanPenjualanExport();
        $collection = $export->collection();

        $this->assertCount(1, $collection);
        $this->assertEquals($completedTransaction->id, $collection->first()->id);
        $this->assertEquals($customer->nama_customer, $collection->first()->customer->nama_customer);
        $this->assertEquals($barang->nama_barang, $collection->first()->barang->nama_barang);
        $this->assertEquals($completedTransaction->jumlah, $collection->first()->jumlah);
        $this->assertEquals($completedTransaction->harga_barang, $collection->first()->harga_barang);
        $this->assertEquals($completedTransaction->total_harga, $collection->first()->total_harga);
        $this->assertEquals('01-01-2023', $collection->first()->tanggal_pembelian->format('d-m-Y'));
        $this->assertEquals('tunai', $collection->first()->tipe_pembayaran);
        $this->assertEquals('Test Address', $collection->first()->alamat_pengantaran);
    }

    public function test_headings_returns_correct_headers()
    {
        $export = new LaporanPenjualanExport();
        $headings = $export->headings();

        $expectedHeadings = [
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

        $this->assertEquals($expectedHeadings, $headings);
    }
}
