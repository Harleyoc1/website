<?php

namespace Tests\Feature\Users;

use App\Livewire\Users\UserCell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use const App\Models\ADMIN_LEVEL;

class UserCellTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_requires_auth(): void
    {
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->set('permissionLevel', true)
            ->call('updatePermissionLevel')
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'permission_level' => 0,
        ]);
    }

    public function test_update_requires_admin(): void
    {
        $this->actingAsUser();
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->set('permissionLevel', true)
            ->call('updatePermissionLevel')
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'permission_level' => 0,
        ]);
    }

    public function test_update_admin(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        Livewire::test(UserCell::class, ['user' => $user])
            ->set('permissionLevel', ADMIN_LEVEL)
            ->call('updatePermissionLevel')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'permission_level' => ADMIN_LEVEL,
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
