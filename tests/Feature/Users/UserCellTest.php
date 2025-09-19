<?php

namespace Tests\Feature\Users;

use App\Livewire\Users\UserCell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserCellTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_requires_auth(): void
    {
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->set('isAdmin', true)
            ->call('updateIsAdmin')
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => false,
        ]);
    }

    public function test_update_requires_admin(): void
    {
        $this->actingAsUser();
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->set('isAdmin', true)
            ->call('updateIsAdmin')
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => false,
        ]);
    }

    public function test_update_admin(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->set('isAdmin', true)
            ->call('updateIsAdmin')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => true,
        ]);
    }

    public function test_delete_requires_auth(): void
    {
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->call('delete')
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);
    }

    public function test_delete_requires_admin(): void
    {
        $this->actingAsUser();
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->call('delete')
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);
    }

    public function test_delete(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->call('delete');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
