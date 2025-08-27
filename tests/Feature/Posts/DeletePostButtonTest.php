<?php

namespace Tests\Feature\Posts;

use App\Livewire\Posts\DeletePostButton;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DeletePostButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_delete_a_post(): void
    {
        $post = Post::factory()->create();

        Livewire::test(DeletePostButton::class, ['post' => $post])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_delete_a_post(): void
    {
        $post = Post::factory()->create();

        Livewire::test(DeletePostButton::class, ['post' => $post])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_deleting_post_removes_the_database_row(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        Livewire::test(DeletePostButton::class, ['post' => $post, 'redirectTo' => 'management.blog.index'])
            ->call('delete');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_deleting_post_removes_content_files(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();
        $post = Post::factory()->create();
        $post->writeContent('Some test content...');

        Livewire::test(DeletePostButton::class, ['post' => $post, 'redirectTo' => 'management.blog.index'])
            ->call('delete');

        Storage::disk('blog')->assertMissing('1/content.md');
        Storage::disk('blog')->assertMissing('1/content.html');
    }

    public function test_deleting_post_redirects(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        Livewire::test(DeletePostButton::class, ['post' => $post, 'redirectTo' => 'management.blog.index'])
            ->call('delete')
            ->assertRedirect(route('management.blog.index'));
    }
}
