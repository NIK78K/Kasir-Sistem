<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_index_displays_customers()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Customer::factory()->count(3)->create();

        $response = $this->get(route('customer.index'));

        $response->assertStatus(200);
        $response->assertViewHas('customers');
        $this->assertCount(3, $response->viewData('customers'));
    }

    public function test_create_displays_form()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $response = $this->get(route('customer.create'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.create');
    }

    public function test_store_creates_customer()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $data = [
            'nama_customer' => 'John Doe',
            'alamat' => '123 Main St',
            'tipe_pembeli' => 'pembeli',
            'no_hp' => '08123456789',
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('customer.store'), $data);

        $response->assertRedirect(route('customer.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customers', [
            'nama_customer' => 'John Doe',
            'alamat' => '123 Main St',
            'tipe_pembeli' => 'pembeli',
            'no_hp' => '08123456789',
        ]);
    }

    public function test_store_fails_with_invalid_data()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $data = [
            'nama_customer' => '',
            'tipe_pembeli' => 'invalid',
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('customer.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['nama_customer', 'tipe_pembeli']);
    }

    public function test_store_allows_duplicate_nama_customer()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        Customer::factory()->create(['nama_customer' => 'John Doe']);

        $data = [
            'nama_customer' => 'John Doe',
            'alamat' => '123 Main St',
            'tipe_pembeli' => 'pembeli',
            'no_hp' => '08123456789',
            '_token' => csrf_token(),
        ];

        $response = $this->post(route('customer.store'), $data);

        $response->assertRedirect(route('customer.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customers', ['nama_customer' => 'John Doe']);
    }

    public function test_edit_displays_form()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();

        $response = $this->get(route('customer.edit', $customer));

        $response->assertStatus(200);
        $response->assertViewHas('customer', $customer);
    }

    public function test_update_modifies_customer()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();

        $data = [
            'nama_customer' => 'Jane Doe',
            'alamat' => '456 Oak St',
            'tipe_pembeli' => 'langganan',
            'no_hp' => '08987654321',
            '_token' => csrf_token(),
        ];

        $response = $this->put(route('customer.update', $customer), $data);

        $response->assertRedirect(route('customer.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customers', [
            'nama_customer' => 'Jane Doe',
            'alamat' => '456 Oak St',
            'tipe_pembeli' => 'langganan',
            'no_hp' => '08987654321',
        ]);
    }

    public function test_update_allows_duplicate_nama_customer()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer1 = Customer::factory()->create(['nama_customer' => 'John']);
        $customer2 = Customer::factory()->create(['nama_customer' => 'Jane']);

        $data = [
            'nama_customer' => 'John',
            'alamat' => '123 Main St',
            'tipe_pembeli' => 'pembeli',
            'no_hp' => '08123456789',
            '_token' => csrf_token(),
        ];

        $response = $this->put(route('customer.update', $customer2), $data);

        $response->assertRedirect(route('customer.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('customers', ['nama_customer' => 'John']);
    }

    public function test_destroy_deletes_customer()
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();

        $response = $this->delete(route('customer.destroy', $customer), ['_token' => csrf_token()]);

        $response->assertRedirect(route('customer.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }
}
