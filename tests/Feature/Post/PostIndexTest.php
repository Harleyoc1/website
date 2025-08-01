<?php

namespace Tests\Feature\Post;

use App\Livewire\Posts\PostCell;
use App\Livewire\Posts\PostIndex;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/management/blog')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/management/blog')->assertStatus(403);
    }

    public function test_admin_users_can_visit_the_page(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/management/blog')->assertStatus(200);
    }

    public function test_page_contains_livewire_component(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/management/blog')->assertSeeLivewire(PostIndex::class);
    }

    public function test_posts_passed_to_view(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        Post::factory()->count(3)->create();

        Livewire::test(PostIndex::class)
            ->assertViewHas('posts', function ($posts) {
                return count($posts) == 3;
            });
    }

    public function test_page_doesnt_contain_cell_component_when_no_posts(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/management/blog')->assertDontSeeLivewire(PostCell::class);
    }

    public function test_page_contains_cell_component_when_posts(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        Post::factory()->create();

        $this->get('/management/blog')->assertSeeLivewire(PostCell::class);
    }

    public function test_deletion_updates_view(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $post = Post::factory()->create();

        Livewire::test(PostCell::class, ['post' => $post])
            ->call('delete');

        Livewire::test(PostIndex::class)->assertDontSeeLivewire(PostCell::class);
    }

}
