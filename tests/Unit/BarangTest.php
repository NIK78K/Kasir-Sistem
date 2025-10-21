<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BarangTest extends TestCase
{
    use RefreshDatabase;

    public function test_barang_has_many_transaksis()
    {
        $barang = Barang::factory()->create();
        $transaksi = Transaksi::factory()->create(['barang_id' => $barang->id]);

        $this->assertInstanceOf(Transaksi::class, $barang->transaksis->first());
        $this->assertEquals($transaksi->id, $barang->transaksis->first()->id);
    }

    public function test_is_new_returns_true_when_no_completed_transactions()
    {
        $barang = Barang::factory()->create();

        $this->assertTrue($barang->isNew());
    }

    public function test_is_new_returns_false_when_has_completed_transactions()
    {
        $barang = Barang::factory()->create();
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'selesai'
        ]);

        $this->assertFalse($barang->isNew());
    }

    public function test_is_new_returns_true_when_only_non_completed_transactions()
    {
        $barang = Barang::factory()->create();
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'batal'
        ]);

        $this->assertTrue($barang->isNew());
    }
}
