<?php

namespace Feature\Blog;

use App\Livewire\Blog\BlogIndex;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response(): void
    {
        $this->get('/blog')->assertSuccessful();
    }

    public function test_contains_blog_livewire_component(): void
    {
        $this->get('/blog')->assertSeeLivewire(BlogIndex::class);
    }

    public function test_can_see_posts(): void
    {
        Post::factory()->create(['title' => 'Test title']);
        Post::factory()->create(['title' => 'Test title 2']);

        $this->get('/blog')
            ->assertSee('Test title')
            ->assertSee('Test title 2');
    }

    public function test_can_see_all_post_info(): void
    {
        $post = Post::factory()->create(['title' => 'Test title', 'summary' => 'Test summary']);

        $this->get('/blog')
            ->assertSee('Test title')
            ->assertSee('Published on ' . $post->created_at->format('j F Y'))
            ->assertSee('Test summary');
    }
}
