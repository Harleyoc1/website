<?php

namespace Feature\Post;

use App\Livewire\Attachments\AttachmentManager;
use App\Livewire\Posts\EditPost;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class EditPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/management/blog/edit/slug')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_page(): void
    {
        $this->actingAsUser();

        $this->get('/management/blog/edit/slug')->assertStatus(403);
    }

    public function test_admin_users_can_visit_the_page(): void
    {
        $this->actingAsAdmin();
        Post::factory()->create(['slug' => 'test-slug']);

        $this->get('/management/blog/edit/test-slug')->assertStatus(200);
    }

    public function test_returns_not_found_when_post_doesnt_exist(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/blog/edit/some-nonexistent-post')->assertStatus(404);
    }

    public function test_page_contains_livewire_component(): void
    {
        $this->actingAsAdmin();
        Post::factory()->create(['slug' => 'test-slug']);

        $this->get('/management/blog/edit/test-slug')->assertSeeLivewire(EditPost::class);
    }

    public function test_guests_cannot_edit_post(): void
    {
        $post = Post::factory()->create();

        Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->call('update')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_edit_post(): void
    {
        $this->actingAsUser();
        $post = Post::factory()->create();

        Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->call('update')
            ->assertStatus(403);
    }

    public function test_cannot_remove_title(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        $response = Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('title', '')
            ->call('update');

        $response->assertHasErrors('title');
    }

    public function test_cannot_remove_slug(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        $response = Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('slug', '')
            ->call('update');

        $response->assertHasErrors('slug');
    }

    public function test_cannot_take_used_slug(): void
    {
        $this->actingAsAdmin();
        $post1 = Post::factory()->create(['slug' => 'test-slug']);
        Post::factory()->create(['slug' => 'test-slug2']);

        $response = Livewire::test(EditPost::class, ['slug' => $post1->slug])
            ->set('slug', 'test-slug2')
            ->call('update');

        $response->assertHasErrors('slug');
    }

    public function test_can_change_other_properties_while_keeping_slug(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        $response = Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('title', 'New Title')
            ->call('update');

        $response->assertHasNoErrors();
    }

    public function test_cannot_remove_summary(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        $response = Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('summary', '')
            ->call('update');

        $response->assertHasErrors('summary');
    }

    public function test_cannot_remove_content(): void
    {
        $this->actingAsAdmin();
        $post = Post::factory()->create();

        $response = Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('content', '')
            ->call('update');

        $response->assertHasErrors('content');
    }

    public function test_post_edit_modifies_database_row(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();
        $post = Post::factory()->create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'summary' => 'Test Summary'
        ]);

        $response = Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('title', 'Modified Title')
            ->set('slug', 'modified-slug')
            ->set('summary', 'Modified summary')
            ->set('content', 'Placeholder...')
            ->call('update');

        $response->assertHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'title' => 'Modified Title',
            'slug' => 'modified-slug',
            'summary' => 'Modified summary'
        ]);
    }

    public function test_modified_post_content_is_written_to_disk(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();
        $post = Post::factory()->create();
        $post->writeContent('Some test content...');

        Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('content', 'Some modified test content...')
            ->call('update');

        Storage::disk('blog')->assertExists('1/content.md');
        $this->assertEquals('Some modified test content...', Storage::disk('blog')->get('1/content.md'));
    }

    public function test_modified_post_content_html_is_written_to_disk(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();
        $post = Post::factory()->create();
        $post->writeContent('Some test content...');

        Livewire::test(EditPost::class, ['slug' => $post->slug])
            ->set('content', 'Some modified test content...')
            ->call('update');

        Storage::disk('blog')->assertExists('1/content.html');
        $this->assertEquals('<p>Some modified test content...</p>', Storage::disk('blog')->get('1/content.html'));
    }

    public function test_page_contains_attachment_manager(): void
    {
        $this->actingAsAdmin()->get('/management/blog/create')->assertSeeLivewire(AttachmentManager::class);
    }

}
