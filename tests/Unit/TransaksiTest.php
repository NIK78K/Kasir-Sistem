<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\Barang;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransaksiTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaksi_belongs_to_customer()
    {
        $customer = Customer::factory()->create();
        $transaksi = Transaksi::factory()->create(['customer_id' => $customer->id]);

        $this->assertInstanceOf(Customer::class, $transaksi->customer);
        $this->assertEquals($customer->id, $transaksi->customer->id);
    }

    public function test_transaksi_belongs_to_barang()
    {
        $barang = Barang::factory()->create();
        $transaksi = Transaksi::factory()->create(['barang_id' => $barang->id]);

        $this->assertInstanceOf(Barang::class, $transaksi->barang);
        $this->assertEquals($barang->id, $transaksi->barang->id);
    }

    public function test_transaksi_fillable_attributes()
    {
        $customer = Customer::factory()->create();
        $barang = Barang::factory()->create();

        $transaksi = Transaksi::factory()->create([
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'jumlah' => 5,
            'harga_barang' => 10000,
            'total_harga' => 50000,
            'uang_dibayar' => 50000,
            'kembalian' => 0,
            'status' => 'selesai',
            'tipe_pembayaran' => 'tunai',
            'alamat_pengantaran' => 'Test Address',
        ]);

        $this->assertEquals($customer->id, $transaksi->customer_id);
        $this->assertEquals($barang->id, $transaksi->barang_id);
        $this->assertEquals(5, $transaksi->jumlah);
        $this->assertEquals(10000, $transaksi->harga_barang);
        $this->assertEquals(50000, $transaksi->total_harga);
        $this->assertEquals(50000, $transaksi->uang_dibayar);
        $this->assertEquals(0, $transaksi->kembalian);
        $this->assertEquals('selesai', $transaksi->status);
        $this->assertEquals('tunai', $transaksi->tipe_pembayaran);
        $this->assertEquals('Test Address', $transaksi->alamat_pengantaran);
    }

    public function test_transaksi_casts()
    {
        $transaksi = Transaksi::factory()->create();

        $this->assertIsInt($transaksi->customer_id);
        $this->assertIsInt($transaksi->barang_id);
        $this->assertIsInt($transaksi->jumlah);
        $this->assertIsInt($transaksi->harga_barang);
        $this->assertIsInt($transaksi->total_harga);
        $this->assertIsInt($transaksi->uang_dibayar);
        $this->assertIsInt($transaksi->kembalian);
        $this->assertIsString($transaksi->status);
        $this->assertIsString($transaksi->tipe_pembayaran);
        $this->assertIsString($transaksi->alamat_pengantaran);
        $this->assertInstanceOf(\Carbon\Carbon::class, $transaksi->tanggal_pembelian);
    }

    public function test_transaksi_dates()
    {
        $transaksi = Transaksi::factory()->create();

        $this->assertContains('created_at', $transaksi->getDates());
        $this->assertContains('updated_at', $transaksi->getDates());
        // tanggal_pembelian is cast to datetime, not in dates array
    }

    public function test_transaksi_factory_creates_valid_transaksi()
    {
        $transaksi = Transaksi::factory()->create();

        $this->assertNotNull($transaksi->id);
        $this->assertNotNull($transaksi->customer_id);
        $this->assertNotNull($transaksi->barang_id);
        $this->assertNotNull($transaksi->jumlah);
        $this->assertNotNull($transaksi->harga_barang);
        $this->assertNotNull($transaksi->total_harga);
        $this->assertNotNull($transaksi->status);
        $this->assertNotNull($transaksi->tanggal_pembelian);
        $this->assertContains($transaksi->status, ['selesai', 'batal', 'return', 'return_partial']);
        $this->assertContains($transaksi->tipe_pembayaran, ['tunai', 'transfer']);
    }
}
