<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_fillable_attributes()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'kasir',
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('kasir', $user->role);
    }

    public function test_user_hidden_attributes()
    {
        $user = User::factory()->create();

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
        $this->assertArrayNotHasKey('invitation_token', $userArray);
    }

    public function test_user_casts()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'last_viewed_barang_at' => now(),
            'last_viewed_customer_at' => now(),
            'invited_at' => now(),
        ]);

        $this->assertIsString($user->name);
        $this->assertIsString($user->email);
        $this->assertIsString($user->role);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->last_viewed_barang_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->last_viewed_customer_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->invited_at);
    }

    public function test_user_password_is_hashed()
    {
        $password = 'password123';
        $user = User::factory()->create(['password' => Hash::make($password)]);

        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertNotEquals($password, $user->password);
    }

    public function test_user_has_role_owner()
    {
        $user = User::factory()->create(['role' => 'owner']);

        $this->assertEquals('owner', $user->role);
    }

    public function test_user_has_role_kasir()
    {
        $user = User::factory()->create(['role' => 'kasir']);

        $this->assertEquals('kasir', $user->role);
    }

    public function test_user_invitation_token_generation()
    {
        $user = User::factory()->create(['invitation_token' => md5('testtoken')]);

        $this->assertNotNull($user->invitation_token);
        $this->assertIsString($user->invitation_token);
        $this->assertEquals(32, strlen($user->invitation_token)); // MD5 hash length
    }

    public function test_user_invited_at_timestamp()
    {
        $user = User::factory()->create(['invited_at' => now()]);

        $this->assertNotNull($user->invited_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $user->invited_at);
    }

    public function test_user_last_viewed_timestamps()
    {
        $user = User::factory()->create();

        $this->assertNull($user->last_viewed_barang_at);
        $this->assertNull($user->last_viewed_customer_at);

        $user->update(['last_viewed_barang_at' => now(), 'last_viewed_customer_at' => now()]);

        $this->assertNotNull($user->fresh()->last_viewed_barang_at);
        $this->assertNotNull($user->fresh()->last_viewed_customer_at);
    }

    public function test_user_email_verification()
    {
        $user = User::factory()->unverified()->create();

        $this->assertFalse($user->hasVerifiedEmail());

        $user->markEmailAsVerified();

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_user_factory_creates_valid_user()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->id);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->role);
        $this->assertContains($user->role, ['owner', 'kasir']);
    }
}
