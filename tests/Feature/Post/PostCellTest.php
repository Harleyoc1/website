<?php

namespace Tests\Feature\Post;

use App\Livewire\Posts\PostCell;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PostCellTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_delete_a_post(): void
    {
        $post = Post::factory()->create();

        Livewire::test(PostCell::class, ['post' => $post])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_delete_a_post(): void
    {
        $post = Post::factory()->create();

        Livewire::test(PostCell::class, ['post' => $post])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_deleting_post_removes_the_database_row(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        Livewire::test(PostCell::class, ['post' => $post])
            ->call('delete');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_deleting_post_removes_content_file(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();
        $post->writeContent('Some test content...');

        Livewire::test(PostCell::class, ['post' => $post])
            ->call('delete');

        Storage::disk('blog')->assertMissing('1.md');
    }

}
