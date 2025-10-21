<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class OwnerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_correct_stats()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        Barang::factory()->count(5)->create();
        Customer::factory()->count(3)->create();
        Transaksi::factory()->count(10)->create();

        $today = Carbon::today();
        Transaksi::factory()->create([
            'total_harga' => 50000,
            'tanggal_pembelian' => $today,
            'status' => 'selesai'
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalProduk', Barang::count());
        $response->assertViewHas('totalCustomer', Customer::count());
        $response->assertViewHas('totalTransaksi', Transaksi::count());
        $response->assertViewHas('penjualanHariIni', Transaksi::whereDate('tanggal_pembelian', $today)->where('status', 'selesai')->sum('total_harga'));
    }

    public function test_data_barang_displays_barangs()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        Barang::factory()->count(3)->create();

        $response = $this->get(route('owner.dataBarang'));

        $response->assertStatus(200);
        $response->assertViewHas('barangs');
        $this->assertCount(3, $response->viewData('barangs'));
    }

    public function test_data_barang_filters_by_kategori()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        Barang::factory()->create(['kategori' => 'Sepeda Pasifik']);
        Barang::factory()->create(['kategori' => 'Sepeda Listrik']);

        $response = $this->get(route('owner.dataBarang', ['kategori' => 'Sepeda Pasifik']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('barangs'));
        $this->assertEquals('Sepeda Pasifik', $response->viewData('barangs')->first()->kategori);
    }

    public function test_data_barang_filters_by_search()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        Barang::factory()->create(['nama_barang' => 'Laptop']);
        Barang::factory()->create(['nama_barang' => 'Mouse']);

        $response = $this->get(route('owner.dataBarang', ['search' => 'Lap']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('barangs'));
        $this->assertEquals('Laptop', $response->viewData('barangs')->first()->nama_barang);
    }

    public function test_data_barang_updates_last_viewed_timestamp()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        $response = $this->get(route('owner.dataBarang'));

        $response->assertStatus(200);
        $user->refresh();
        $this->assertNotNull($user->last_viewed_barang_at);
    }

    public function test_data_customer_displays_customers()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        Customer::factory()->count(3)->create();

        $response = $this->get(route('owner.dataCustomer'));

        $response->assertStatus(200);
        $response->assertViewHas('customers');
        $this->assertCount(3, $response->viewData('customers'));
    }

    public function test_data_customer_updates_last_viewed_timestamp()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        $response = $this->get(route('owner.dataCustomer'));

        $response->assertStatus(200);
        $user->refresh();
        $this->assertNotNull($user->last_viewed_customer_at);
    }

    public function test_laporan_penjualan_displays_completed_transactions()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        Transaksi::factory()->create(['status' => 'selesai']);
        Transaksi::factory()->create(['status' => 'batal']);

        $response = $this->get(route('owner.laporanPenjualan'));

        $response->assertStatus(200);
        $response->assertViewHas('transaksis');
        $this->assertCount(1, $response->viewData('transaksis'));
        $this->assertEquals('selesai', $response->viewData('transaksis')->first()->status);
    }

    public function test_laporan_barang_return_displays_return_transactions()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        Transaksi::factory()->create(['status' => 'return']);
        Transaksi::factory()->create(['status' => 'return_partial']);
        Transaksi::factory()->create(['status' => 'selesai']);

        $response = $this->get(route('owner.laporanBarangReturn'));

        $response->assertStatus(200);
        $response->assertViewHas('transaksis');
        $this->assertCount(2, $response->viewData('transaksis'));
    }
}
