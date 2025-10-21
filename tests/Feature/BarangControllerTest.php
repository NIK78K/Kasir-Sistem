<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BarangControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_barangs()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Barang::factory()->count(3)->create();

        $response = $this->get(route('barang.index'));

        $response->assertStatus(200);
        $response->assertViewHas('barangs');
        $this->assertCount(3, $response->viewData('barangs'));
    }

    public function test_index_filters_by_kategori()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Barang::factory()->create(['kategori' => 'Sepeda Pasifik']);
        Barang::factory()->create(['kategori' => 'Sepeda Listrik']);

        $response = $this->get(route('barang.index', ['kategori' => 'Sepeda Pasifik']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('barangs'));
        $this->assertEquals('Sepeda Pasifik', $response->viewData('barangs')->first()->kategori);
    }

    public function test_index_filters_by_search()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Barang::factory()->create(['nama_barang' => 'Laptop']);
        Barang::factory()->create(['nama_barang' => 'Mouse']);

        $response = $this->get(route('barang.index', ['search' => 'Lap']));

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('barangs'));
        $this->assertEquals('Laptop', $response->viewData('barangs')->first()->nama_barang);
    }

    public function test_create_displays_form()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $response = $this->get(route('barang.create'));

        $response->assertStatus(200);
        $response->assertViewIs('barang.create');
    }

    public function test_store_creates_barang()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Storage::fake('public');

        $data = [
            'nama_barang' => 'Test Barang',
            'harga' => 10000,
            'harga_grosir' => 8000,
            'stok' => 50,
            'kategori' => 'Sepeda Pasifik',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
        ];

        $response = $this->post(route('barang.store'), $data);

        $response->assertRedirect(route('barang.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('barangs', [
            'nama_barang' => 'Test Barang',
            'harga' => 10000,
            'harga_grosir' => 8000,
            'stok' => 50,
            'kategori' => 'Sepeda Pasifik',
        ]);
    }

    public function test_store_fails_with_invalid_data()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $data = [
            'nama_barang' => '',
            'harga' => 'invalid',
            'stok' => -1,
            'kategori' => '',
        ];

        $response = $this->post(route('barang.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['nama_barang', 'harga', 'stok', 'kategori']);
    }

    public function test_store_fails_with_duplicate_nama_barang()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Barang::factory()->create(['nama_barang' => 'Duplicate Name']);

        $data = [
            'nama_barang' => 'Duplicate Name',
            'harga' => 10000,
            'stok' => 10,
            'kategori' => 'Sepeda Pasifik',
        ];

        $response = $this->post(route('barang.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nama_barang');
    }

    public function test_edit_displays_form()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $barang = Barang::factory()->create();

        $response = $this->get(route('barang.edit', $barang));

        $response->assertStatus(200);
        $response->assertViewHas('barang', $barang);
    }

    public function test_update_modifies_barang()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $barang = Barang::factory()->create();

        $data = [
            'nama_barang' => 'Updated Name',
            'harga' => 20000,
            'harga_grosir' => 15000,
            'stok' => 30,
            'kategori' => 'Sepeda Listrik',
        ];

        $response = $this->put(route('barang.update', $barang), $data);

        $response->assertRedirect(route('barang.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'nama_barang' => 'Updated Name',
            'harga' => 20000,
            'harga_grosir' => 15000,
            'stok' => 30,
            'kategori' => 'Sepeda Listrik',
        ]);
    }

    public function test_update_fails_with_duplicate_nama_barang()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $barang1 = Barang::factory()->create(['nama_barang' => 'Name 1']);
        $barang2 = Barang::factory()->create(['nama_barang' => 'Name 2']);

        $data = [
            'nama_barang' => 'Name 1',
            'harga' => 10000,
            'stok' => 10,
            'kategori' => 'Sepeda Pasifik',
        ];

        $response = $this->put(route('barang.update', $barang2), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nama_barang');
    }

    public function test_destroy_deletes_barang()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $barang = Barang::factory()->create();

        $response = $this->delete(route('barang.destroy', $barang));

        $response->assertRedirect(route('barang.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('barangs', ['id' => $barang->id]);
    }
}
