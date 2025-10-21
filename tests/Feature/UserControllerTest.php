<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_users()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        User::factory()->count(3)->create();

        $response = $this->get(route('user.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $this->assertCount(4, $response->viewData('users')); // including the authenticated user
    }

    public function test_create_displays_form()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        $response = $this->get(route('user.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.create');
    }

    public function test_store_creates_user()
    {
        $authenticatedUser = User::factory()->create(['role' => 'owner']);
        $this->actingAs($authenticatedUser);

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'kasir',
        ];

        $response = $this->post(route('user.store'), $data);

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'kasir',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_store_fails_with_invalid_data()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'role' => 'invalid',
        ];

        $response = $this->post(route('user.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_store_fails_with_duplicate_email()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        User::factory()->create(['email' => 'john@example.com']);

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'kasir',
        ];

        $response = $this->post(route('user.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    public function test_edit_displays_form()
    {
        $authenticatedUser = User::factory()->create(['role' => 'owner']);
        $this->actingAs($authenticatedUser);

        $user = User::factory()->create();

        $response = $this->get(route('user.edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
    }

    public function test_update_modifies_user()
    {
        $authenticatedUser = User::factory()->create(['role' => 'owner']);
        $this->actingAs($authenticatedUser);

        $user = User::factory()->create();

        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'role' => 'owner',
        ];

        $response = $this->put(route('user.update', $user->id), $data);

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => 'owner',
        ]);

        $updatedUser = User::find($user->id);
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
    }

    public function test_update_without_password_does_not_change_password()
    {
        $authenticatedUser = User::factory()->create(['role' => 'owner']);
        $this->actingAs($authenticatedUser);

        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => 'owner',
        ];

        $response = $this->put(route('user.update', $user->id), $data);

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success');

        $updatedUser = User::find($user->id);
        $this->assertTrue(Hash::check('oldpassword', $updatedUser->password));
    }

    public function test_update_fails_with_duplicate_email()
    {
        $authenticatedUser = User::factory()->create(['role' => 'owner']);
        $this->actingAs($authenticatedUser);

        $user1 = User::factory()->create(['email' => 'john@example.com']);
        $user2 = User::factory()->create(['email' => 'jane@example.com']);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'john@example.com',
            'role' => 'kasir',
        ];

        $response = $this->put(route('user.update', $user2->id), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    public function test_destroy_deletes_user()
    {
        $authenticatedUser = User::factory()->create(['role' => 'owner']);
        $this->actingAs($authenticatedUser);

        $user = User::factory()->create();

        $response = $this->delete(route('user.destroy', $user->id));

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
