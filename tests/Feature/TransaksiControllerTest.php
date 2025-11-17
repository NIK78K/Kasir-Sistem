<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class TransaksiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_transaction_page()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        Barang::factory()->count(3)->create();

        $response = $this->get(route('transaksi.index'));

        $response->assertStatus(200);
        $response->assertViewHas(['customers', 'barangs', 'cart']);
    }

    public function test_index_with_customer_selection()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        Barang::factory()->count(3)->create();

        $response = $this->get(route('transaksi.index', ['customer_id' => $customer->id]));

        $response->assertStatus(200);
        $response->assertViewHas('customer', $customer);
        $this->assertEquals($customer->id, session('selected_customer_id'));
    }

    public function test_add_to_cart_requires_customer()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $barang = Barang::factory()->create(['stok' => 10]);

        $response = $this->post(route('transaksi.addToCart'), [
            'barang_id' => $barang->id,
            'jumlah' => 2,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHasErrors('customer');
    }

    public function test_add_to_cart_for_regular_customer()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create(['tipe_pembeli' => 'pembeli']);
        $barang = Barang::factory()->create(['stok' => 10, 'harga' => 10000]);

        Session::put('selected_customer_id', $customer->id);

        $response = $this->post(route('transaksi.addToCart'), [
            'barang_id' => $barang->id,
            'jumlah' => 2,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $cart = session('cart');
        $this->assertCount(1, $cart);
        $this->assertEquals($barang->id, $cart[0]['barang_id']);
        $this->assertEquals(10000, $cart[0]['harga']);
        $this->assertEquals('biasa', $cart[0]['tipe_harga']);
    }

    public function test_add_to_cart_for_grosir_customer()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create(['tipe_pembeli' => 'langganan']);
        $barang = Barang::factory()->create(['stok' => 10, 'harga_grosir' => 8000]);

        Session::put('selected_customer_id', $customer->id);

        $response = $this->post(route('transaksi.addToCart'), [
            'barang_id' => $barang->id,
            'jumlah' => 2,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $cart = session('cart');
        $this->assertEquals(8000, $cart[0]['harga']);
        $this->assertEquals('grosir', $cart[0]['tipe_harga']);
    }

    public function test_add_to_cart_accumulates_quantity()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create(['tipe_pembeli' => 'pembeli']);
        $barang = Barang::factory()->create(['stok' => 10, 'harga' => 10000]);

        Session::put('selected_customer_id', $customer->id);

        $this->post(route('transaksi.addToCart'), [
            'barang_id' => $barang->id,
            'jumlah' => 2,
            '_token' => csrf_token(),
        ]);

        $this->post(route('transaksi.addToCart'), [
            'barang_id' => $barang->id,
            'jumlah' => 3,
            '_token' => csrf_token(),
        ]);

        $cart = session('cart');
        $this->assertCount(1, $cart);
        $this->assertEquals(5, $cart[0]['jumlah']);
    }

    public function test_remove_from_cart()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $cart = [['barang_id' => 1, 'nama_barang' => 'Test', 'harga' => 10000, 'tipe_harga' => 'biasa', 'jumlah' => 2]];
        Session::put('cart', $cart);

        $response = $this->delete(route('transaksi.removeFromCart', 0), ['_token' => csrf_token()]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEmpty(session('cart'));
    }

    public function test_confirm_order_requires_customer()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $response = $this->post(route('transaksi.confirmOrder'), [
            'tipe_pembayaran' => 'tunai',
            'uang_dibayar' => 10000,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('customer_id');
    }

    public function test_confirm_order_requires_cart()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();

        $response = $this->post(route('transaksi.confirmOrder'), [
            'customer_id' => $customer->id,
            'tipe_pembayaran' => 'tunai',
            'uang_dibayar' => 10000,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('cart');
    }

    public function test_confirm_order_creates_transactions()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create(['tipe_pembeli' => 'pembeli']);
        $barang = Barang::factory()->create(['stok' => 10, 'harga' => 10000]);

        $cart = [
            [
                'barang_id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'harga' => 10000,
                'tipe_harga' => 'biasa',
                'jumlah' => 2,
            ]
        ];

        Session::put('selected_customer_id', $customer->id);
        Session::put('cart', $cart);

        $response = $this->post(route('transaksi.confirmOrder'), [
            'customer_id' => $customer->id,
            'tipe_pembayaran' => 'tunai',
            'uang_dibayar' => 25000,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('transaksi.confirm'));
        $response->assertSessionHas('success');

        $transaksi = Transaksi::where('customer_id', $customer->id)->first();

        $this->assertDatabaseHas('transaksis', [
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'jumlah' => 2,
            'harga_barang' => 10000,
            'total_harga' => 20000,
            'uang_dibayar' => 25000,
            'kembalian' => 5000,
            'status' => 'selesai',
        ]);

        // Assert order_id is generated and starts with 'ORD-'
        $this->assertNotNull($transaksi->order_id);
        $this->assertStringStartsWith('ORD-', $transaksi->order_id);

        $barang->refresh();
        $this->assertEquals(8, $barang->stok);
    }

    public function test_confirm_order_fails_with_insufficient_stock()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create(['tipe_pembeli' => 'pembeli']);
        $barang = Barang::factory()->create(['stok' => 1, 'harga' => 10000]);

        $cart = [
            [
                'barang_id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'harga' => 10000,
                'tipe_harga' => 'biasa',
                'jumlah' => 5,
            ]
        ];

        Session::put('selected_customer_id', $customer->id);
        Session::put('cart', $cart);

        $response = $this->post(route('transaksi.confirmOrder'), [
            'customer_id' => $customer->id,
            'tipe_pembayaran' => 'tunai',
            'uang_dibayar' => 50000,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('error');
    }

    public function test_confirm_displays_confirmation_page()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $transaksi = Transaksi::factory()->create(['status' => 'selesai']);
        Session::put('last_order_ids', [$transaksi->id]);

        $response = $this->get(route('transaksi.confirm'));

        $response->assertStatus(200);
        $response->assertViewHas(['transaksis', 'customer', 'total']);
    }

    public function test_list_returnable_transaksi()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Transaksi::factory()->create(['status' => 'selesai']);
        Transaksi::factory()->create(['status' => 'batal']);

        $response = $this->get(route('transaksi.listReturnable'));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
        $this->assertCount(1, $response->viewData('orders'));
    }

    public function test_store_creates_transaction()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $barang = Barang::factory()->create(['stok' => 10, 'harga' => 10000]);

        $response = $this->post(route('transaksi.store'), [
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'jumlah' => 2,
            'tipe_pembayaran' => 'tunai',
            'tanggal_pembelian' => now()->format('Y-m-d'),
            'alamat_pengantaran' => 'Test Address',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHas('success');

        $transaksi = Transaksi::where('customer_id', $customer->id)->first();

        $this->assertDatabaseHas('transaksis', [
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'jumlah' => 2,
            'harga_barang' => 10000,
            'total_harga' => 20000,
            'status' => 'selesai',
        ]);

        // Assert order_id is generated and starts with 'ORD-'
        $this->assertNotNull($transaksi->order_id);
        $this->assertStringStartsWith('ORD-', $transaksi->order_id);

        $barang->refresh();
        $this->assertEquals(8, $barang->stok);
    }

    public function test_store_fails_with_insufficient_stock()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $barang = Barang::factory()->create(['stok' => 1]);

        $response = $this->post(route('transaksi.store'), [
            'customer_id' => $customer->id,
            'barang_id' => $barang->id,
            'jumlah' => 5,
            'tipe_pembayaran' => 'tunai',
            'tanggal_pembelian' => now()->format('Y-m-d'),
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('jumlah');
    }



    public function test_barang_return_shows_form()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $transaksi = Transaksi::factory()->create();

        $response = $this->get(route('transaksi.barangReturn', $transaksi->id));

        $response->assertStatus(200);
        $response->assertViewHas('transaksi', $transaksi);
    }

    public function test_return_processes_partial_return()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $transaksi = Transaksi::factory()->create(['status' => 'selesai', 'jumlah' => 5, 'order_id' => 'ORD-20251107-0123']);
        $barang = $transaksi->barang;
        $originalStok = $barang->stok;
        $barang->decrement('stok', 5);

        $response = $this->post(route('transaksi.return', $transaksi->id), [
            'items' => [
                [
                    'transaksi_id' => $transaksi->id,
                    'return' => '1',
                    'jumlah_return' => 2,
                ]
            ],
            'alasan_return' => 'Test reason',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $transaksi->refresh();
        $this->assertEquals('selesai', $transaksi->status);

        // Assert that a new return transaction is created with the same order_id
        $returnTransaksi = Transaksi::where('parent_transaksi_id', $transaksi->id)->first();
        $this->assertNotNull($returnTransaksi);
        $this->assertEquals('ORD-20251107-0123', $returnTransaksi->order_id);
        $this->assertEquals('return_partial', $returnTransaksi->status);

        $barang->refresh();
        $this->assertEquals($originalStok - 3, $barang->stok); // Stock decreased by purchase amount, increased by return amount: original - 5 + 2 = original - 3
    }

    public function test_return_processes_full_return()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $transaksi = Transaksi::factory()->create(['status' => 'selesai', 'jumlah' => 3]);
        $barang = $transaksi->barang;
        $originalStok = $barang->stok;
        $barang->decrement('stok', 3);

        $response = $this->post(route('transaksi.return', $transaksi->id), [
            'items' => [
                [
                    'transaksi_id' => $transaksi->id,
                    'return' => '1',
                    'jumlah_return' => 3,
                ]
            ],
            'alasan_return' => 'Test reason',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $transaksi->refresh();
        $this->assertEquals('return', $transaksi->status);

        $barang->refresh();
        $this->assertEquals($originalStok, $barang->stok); // Stock restored to original
    }

    public function test_return_fails_for_invalid_transaction()
    {
        $user = \App\Models\User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $transaksi = Transaksi::factory()->create(['status' => 'batal']);

        $response = $this->post(route('transaksi.return', $transaksi->id), [
            'items' => [
                [
                    'transaksi_id' => $transaksi->id,
                    'return' => '1',
                    'jumlah_return' => 1,
                ]
            ],
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('transaksi_id');
    }
}
