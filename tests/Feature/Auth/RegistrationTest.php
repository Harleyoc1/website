<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\Register;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register/token');

        $response->assertStatus(200);
    }

    public function test_cannot_register_without_token(): void
    {
        Livewire::test(Register::class, ['non-existent-token', 'email' => 'test@email.com'])
            ->set('name', 'Test User')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertHasErrors(['email']);
    }

    public function test_new_users_can_register_with_token(): void
    {
        DB::table('registration_tokens')->insert([
            'email' => 'test@email.com',
            'is_admin' => false,
            'token' => Hash::make('test-token'),
            'created_at' => now()
        ]);

        $response = Livewire::withQueryParams(['email' => 'test@email.com'])
            ->test(Register::class, ['token' => 'test-token'])
            ->set('name', 'Test User')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'is_admin' => false
        ]);
    }

    public function test_new_users_granted_admin_when_set(): void
    {
        DB::table('registration_tokens')->insert([
            'email' => 'test@email.com',
            'is_admin' => true,
            'token' => Hash::make('test-token'),
            'created_at' => now()
        ]);

        $response = Livewire::withQueryParams(['email' => 'test@email.com'])
            ->test(Register::class, ['token' => 'test-token'])
            ->set('name', 'Test User')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'is_admin' => true
        ]);
    }
}
