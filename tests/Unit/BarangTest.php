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

    public function test_barang_fillable_attributes()
    {
        $barang = Barang::factory()->create([
            'nama_barang' => 'Test Barang',
            'harga' => 10000,
            'harga_grosir' => 8000,
            'stok' => 50,
            'kategori' => 'Sepeda Pasifik',
            'gambar' => 'test.jpg',
        ]);

        $this->assertEquals('Test Barang', $barang->nama_barang);
        $this->assertEquals(10000, $barang->harga);
        $this->assertEquals(8000, $barang->harga_grosir);
        $this->assertEquals(50, $barang->stok);
        $this->assertEquals('Sepeda Pasifik', $barang->kategori);
        $this->assertEquals('test.jpg', $barang->gambar);
    }

    public function test_barang_casts()
    {
        $barang = Barang::factory()->create();

        $this->assertIsString($barang->nama_barang);
        $this->assertIsInt($barang->harga);
        $this->assertIsInt($barang->harga_grosir);
        $this->assertIsInt($barang->stok);
        $this->assertIsString($barang->kategori);
        $this->assertNull($barang->gambar); // gambar can be null
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

    public function test_is_new_returns_false_when_has_multiple_completed_transactions()
    {
        $barang = Barang::factory()->create();
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'selesai'
        ]);
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'selesai'
        ]);

        $this->assertFalse($barang->isNew());
    }

    public function test_is_new_returns_false_when_has_mixed_transaction_statuses()
    {
        $barang = Barang::factory()->create();
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'selesai'
        ]);
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'batal'
        ]);

        $this->assertFalse($barang->isNew());
    }

    public function test_is_new_returns_true_when_only_return_transactions()
    {
        $barang = Barang::factory()->create();
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'return'
        ]);
        Transaksi::factory()->create([
            'barang_id' => $barang->id,
            'status' => 'return_partial'
        ]);

        $this->assertTrue($barang->isNew());
    }

    public function test_barang_factory_creates_valid_barang()
    {
        $barang = Barang::factory()->create();

        $this->assertNotNull($barang->id);
        $this->assertNotNull($barang->nama_barang);
        $this->assertNotNull($barang->harga);
        $this->assertNotNull($barang->stok);
        $this->assertNotNull($barang->kategori);
        $this->assertContains($barang->kategori, ['Sepeda Pasifik', 'Sepeda Listrik', 'Ban', 'Sepeda Stroller', 'Sparepart']);
    }
}
