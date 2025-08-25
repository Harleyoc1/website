<?php

namespace Tests\Feature\Post;

use App\Livewire\Attachments\AttachmentManager;
use App\Livewire\Posts\CreatePost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/management/blog/create')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_page(): void
    {
        $this->actingAsUser();

        $this->get('/management/blog/create')->assertStatus(403);
    }

    public function test_admin_users_can_visit_the_page(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/blog/create')->assertStatus(200);
    }

    public function test_page_contains_livewire_component(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/blog/create')->assertSeeLivewire(CreatePost::class);
    }

    public function test_guests_cannot_create_post(): void
    {
        Livewire::test(CreatePost::class)
            ->call('store')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_create_post(): void
    {
        $this->actingAsUser();

        Livewire::test(CreatePost::class)
            ->call('store')
            ->assertStatus(403);
    }

    public function test_cannot_create_post_without_title(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreatePost::class)
            ->set('slug', 'test-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        $response->assertHasErrors('title');
    }

    public function test_cannot_create_post_without_slug(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        $response->assertHasErrors('slug');
    }

    public function test_cannot_create_post_with_used_slug(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'repeated-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        $response->assertHasNoErrors();

        $response = Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'repeated-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        $response->assertHasErrors('slug');
    }

    public function test_cannot_create_post_without_summary(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('content', 'Some test content...')
            ->call('store');

        $response->assertHasErrors('summary');
    }

    public function test_cannot_create_post_without_content(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('summary', 'Test summary')
            ->call('store');

        $response->assertHasErrors('content');
    }

    public function test_post_creation_adds_database_row(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();

        $response = Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        $response->assertHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'summary' => 'Test summary'
        ]);
    }

    public function test_redirects_on_successful_post_creation(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();

        $response = Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('management.blog.index', absolute: false));
    }

    public function test_post_content_is_written_to_disk(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();

        Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        Storage::disk('blog')->assertExists('1/content.md');
        $this->assertEquals('Some test content...', Storage::disk('blog')->get('1/content.md'));
    }

    public function test_post_content_html_is_written_to_disk(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();

        Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store');

        Storage::disk('blog')->assertExists('1/content.html');
        $this->assertEquals('<p>Some test content...</p>', Storage::disk('blog')->get('1/content.html'));
    }

    public function test_page_contains_attachment_manager(): void
    {
        $this->actingAsAdmin()->get('/management/blog/create')->assertSeeLivewire(AttachmentManager::class);
    }

    public function test_attachment_upload_event_dispatched_on_store(): void
    {
        Storage::fake('blog');
        $this->actingAsAdmin();

        Livewire::test(CreatePost::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('summary', 'Test summary')
            ->set('content', 'Some test content...')
            ->call('store')
            ->assertDispatched('uploadAttachments', 'blog', '1/attachments');
    }

}
