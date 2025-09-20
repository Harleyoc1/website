<?php

namespace Tests\Feature\Projects;

use App\Livewire\Projects\CreateProject;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/management/portfolio/create')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_page(): void
    {
        $this->actingAsUser();

        $this->get('/management/portfolio/create')->assertStatus(403);
    }

    public function test_admin_users_can_visit_the_page(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/portfolio/create')->assertStatus(200);
    }

    public function test_page_contains_livewire_component(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/portfolio/create')->assertSeeLivewire(CreateProject::class);
    }

    public function test_guests_cannot_create_project(): void
    {
        Livewire::test(CreateProject::class)
            ->call('store')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_create_project(): void
    {
        $this->actingAsUser();

        Livewire::test(CreateProject::class)
            ->call('store')
            ->assertStatus(403);
    }

    public function test_cannot_create_project_without_title(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('slug', 'test-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('title');
    }

    public function test_cannot_create_project_without_slug(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('slug');
    }

    public function test_cannot_create_project_with_used_slug(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'repeated-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasNoErrors();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'repeated-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('slug');
    }

    public function test_cannot_create_project_without_tools(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('tools');
    }

    public function test_cannot_create_project_without_cover_image(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('tools', 'Test languages')
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('coverImage');
    }

    public function test_cannot_create_project_with_invalid_cover_image(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->create('test-img.txt'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('coverImage');
    }

    public function test_cannot_create_project_without_cover_image_filename(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('coverImageFilename');
    }

    public function test_cannot_create_project_with_used_cover_image_filename(): void
    {
        $this->actingAsAdmin();
        Project::factory()->create(['cover_img_filename' => 'test-img.png', 'slug' => 'test-slug']);

        $response = Livewire::test(CreateProject::class)
            ->set('coverImageFilename', 'test-img.png')
            ->call('store');

        $response->assertHasErrors('coverImageFilename');
    }

    public function test_cannot_create_project_without_summary(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response->assertHasErrors('summary');
    }

    public function test_cannot_create_project_with_invalid_repo_link(): void
    {
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://invalid link/')
            ->call('store');

        $response->assertHasErrors('repoLink');
    }

    public function test_project_creation_adds_database_row(): void
    {
        Storage::fake('portfolio');
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'test-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->set('standout', true)
            ->call('store');

        $response->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'tools' => 'Test languages',
            'cover_img_filename' => 'test-img.png',
            'summary' => 'Test summary',
            'repo_link' => 'https://test.link/',
            'standout' => 1
        ]);
    }

    public function test_redirects_on_successful_project_creation(): void
    {
        Storage::fake('portfolio');
        $this->actingAsAdmin();

        $response = Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'repeated-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('management.portfolio.index', absolute: false));
    }

    public function test_cover_image_is_written_to_disk(): void
    {
        Storage::fake('portfolio');
        $this->actingAsAdmin();

        Livewire::test(CreateProject::class)
            ->set('title', 'Test Title')
            ->set('slug', 'repeated-slug')
            ->set('tools', 'Test languages')
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->set('coverImageFilename', 'test-img.png')
            ->set('summary', 'Test summary')
            ->set('repoLink', 'https://test.link/')
            ->call('store');

        Storage::disk('portfolio')->assertExists('cover-images/test-img.png');
    }

}
