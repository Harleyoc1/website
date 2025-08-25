<?php

namespace Tests\Feature\Blog;

use App\Livewire\Blog\ShowPost;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ShowBlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_not_found_when_post_doesnt_exist(): void
    {
        $this->get('/blog/some-nonexistent-post')->assertNotFound();
    }

    public function test_returns_success_when_post_exist(): void
    {
        Storage::fake('blog');
        Post::factory()->create(['slug' => 'test-slug']);

        $this->get('/blog/test-slug')->assertSuccessful();
    }

    public function test_page_contains_livewire_component(): void
    {
        Storage::fake('blog');
        Post::factory()->create(['slug' => 'test-slug']);

        $this->get('/blog/test-slug')->assertSeeLivewire(ShowPost::class);
    }

    public function test_page_displays_title(): void
    {
        Storage::fake('blog');
        Post::factory()->create(['title' => 'A test title', 'slug' => 'test-slug']);

        $this->get('/blog/test-slug')->assertSee('A test title');
    }

    public function test_page_displays_date_published(): void
    {
        Storage::fake('blog');
        $post = Post::factory()->create(['slug' => 'test-slug']);

        $this->get('/blog/test-slug')->assertSee('Published on ' . $post->created_at->format('j F Y'));
    }

    public function test_page_displays_content(): void
    {
        Storage::fake('blog');
        $post = Post::factory()->create(['slug' => 'test-slug']);
        $post->writeContent('Some test content which we can hopefully see...');

        $this->get('/blog/test-slug')->assertSee('Some test content which we can hopefully see...');
    }

    public function test_guests_cannot_delete_a_post(): void
    {
        Post::factory()->create(['slug' => 'test-slug']);

        Livewire::test(ShowPost::class, ['slug' => 'test-slug'])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_delete_a_post(): void
    {
        Post::factory()->create(['slug' => 'test-slug']);

        Livewire::test(ShowPost::class, ['slug' => 'test-slug'])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_deleting_post_removes_the_database_row(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create(['slug' => 'test-slug']);

        Livewire::test(ShowPost::class, ['slug' => 'test-slug'])
            ->call('delete');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_deleting_post_removes_content_files(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();
        $post = Post::factory()->create(['slug' => 'test-slug']);
        $post->writeContent('Some test content...');

        Livewire::test(ShowPost::class, ['slug' => 'test-slug'])
            ->call('delete');

        Storage::disk('blog')->assertMissing('1/content.md');
        Storage::disk('blog')->assertMissing('1/content.html');
    }


}
