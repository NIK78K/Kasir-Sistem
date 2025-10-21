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
}
