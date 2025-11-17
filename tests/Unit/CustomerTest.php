<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_has_many_transaksis()
    {
        $customer = Customer::factory()->create();
        $transaksi = Transaksi::factory()->create(['customer_id' => $customer->id]);

        $this->assertInstanceOf(Transaksi::class, $customer->transaksis->first());
        $this->assertEquals($transaksi->id, $customer->transaksis->first()->id);
    }

    public function test_customer_fillable_attributes()
    {
        $customer = Customer::factory()->create([
            'nama_customer' => 'Test Customer',
            'alamat' => 'Test Address',
            'tipe_pembeli' => 'pembeli',
            'no_hp' => '08123456789',
        ]);

        $this->assertEquals('Test Customer', $customer->nama_customer);
        $this->assertEquals('Test Address', $customer->alamat);
        $this->assertEquals('pembeli', $customer->tipe_pembeli);
        $this->assertEquals('08123456789', $customer->no_hp);
    }

    public function test_customer_casts()
    {
        $customer = Customer::factory()->create();

        $this->assertIsString($customer->nama_customer);
        $this->assertIsString($customer->alamat);
        $this->assertIsString($customer->tipe_pembeli);
        $this->assertIsString($customer->no_hp);
    }

    public function test_customer_uses_soft_deletes()
    {
        $customer = Customer::factory()->create();

        $customer->delete();

        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
        $this->assertNotNull($customer->deleted_at);
    }

    public function test_customer_can_be_restored()
    {
        $customer = Customer::factory()->create();

        $customer->delete();
        $customer->restore();

        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
        $this->assertNull($customer->deleted_at);
    }

    public function test_customer_can_be_force_deleted()
    {
        $customer = Customer::factory()->create();

        $customer->forceDelete();

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_customer_with_trashed_includes_soft_deleted()
    {
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();
        $customer2->delete();

        $customers = Customer::withTrashed()->get();

        $this->assertCount(2, $customers);
        $this->assertTrue($customers->contains($customer1));
        $this->assertTrue($customers->contains($customer2));
    }

    public function test_customer_only_trashed_returns_only_soft_deleted()
    {
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();
        $customer2->delete();

        $trashedCustomers = Customer::onlyTrashed()->get();

        $this->assertCount(1, $trashedCustomers);
        $this->assertTrue($trashedCustomers->contains($customer2));
        $this->assertFalse($trashedCustomers->contains($customer1));
    }
}
