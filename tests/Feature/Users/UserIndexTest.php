<?php

namespace Tests\Feature\Users;

use App\Livewire\Users\UserCell;
use App\Livewire\Users\UserIndex;
use App\Mail\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/management/users')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_page(): void
    {
        $this->actingAsUser();

        $this->get('/management/users')->assertStatus(403);
    }

    public function test_admin_users_can_visit_the_page(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/users')->assertStatus(200);
    }

    public function test_page_contains_livewire_components(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/users')
            ->assertSeeLivewire(UserIndex::class)
            ->assertSeeLivewire(UserCell::class);
    }

    public function test_users_passed_to_view(): void
    {
        $this->actingAsAdmin();
        User::factory()->count(3)->create();

        Livewire::test(UserIndex::class)
            ->assertViewHas('users', function ($users) {
                // 4 since actingAsAdmin creates a user too
                return count($users) == 4;
            });
    }

    public function test_can_see_user_data(): void
    {
        $this->actingAsAdmin();
        User::factory()->count(2)->create();

        $response = $this->get('/management/users');

        User::all()->each(function ($user) use ($response) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        });
    }

    public function test_add_user_fails_with_invalid_email(): void
    {
        $this->actingAsAdmin();

        Livewire::test(UserIndex::class)
            ->set('newUserEmail', 'invalid')
            ->call('add')
            ->assertHasErrors(['newUserEmail']);
    }

    public function test_add_user_fails_with_taken_email(): void
    {
        $this->actingAsAdmin();

        User::factory()->create(['email' => 'taken@email.com']);

        Livewire::test(UserIndex::class)
            ->set('newUserEmail', 'taken@email.com')
            ->call('add')
            ->assertHasErrors(['newUserEmail']);
    }

    public function test_add_user_updates_registration_tokens_table(): void
    {
        $this->actingAsAdmin();

        Livewire::test(UserIndex::class)
            ->set('newUserEmail', 'test@email.com')
            ->call('add');

        $this->assertDatabaseHas('registration_tokens', [
            'email' => 'test@email.com',
            'is_admin' => false
        ]);
    }

    public function test_add_user_sends_registration_email(): void
    {
        Mail::fake();
        $this->actingAsAdmin();

        Livewire::test(UserIndex::class)
            ->set('newUserEmail', 'test@email.com')
            ->call('add');

        Mail::assertSent(Registration::class, 'test@email.com');
    }

}
