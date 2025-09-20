<?php

namespace Tests\Feature\Projects;

use App\Livewire\Projects\EditProject;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class EditProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/management/portfolio/edit/slug')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_page(): void
    {
        $this->actingAsUser();

        $this->get('/management/portfolio/edit/slug')->assertStatus(403);
    }

    public function test_admin_users_can_visit_the_page(): void
    {
        $this->actingAsAdmin();
        Project::factory()->create(['slug' => 'test-slug']);

        $this->get('/management/portfolio/edit/test-slug')->assertStatus(200);
    }

    public function test_returns_not_found_when_project_doesnt_exist(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/portfolio/edit/some-nonexistent-project')->assertStatus(404);
    }

    public function test_page_contains_livewire_component(): void
    {
        $this->actingAsAdmin();
        Project::factory()->create(['slug' => 'test-slug']);

        $this->get('/management/portfolio/edit/test-slug')->assertSeeLivewire(EditProject::class);
    }

    public function test_guests_cannot_edit_project(): void
    {
        $project = Project::factory()->create();

        Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->call('update')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_edit_project(): void
    {
        $this->actingAsUser();
        $project = Project::factory()->create();

        Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->call('update')
            ->assertStatus(403);
    }

    public function test_cannot_remove_title(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create();

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('title', '')
            ->call('update');

        $response->assertHasErrors('title');
    }

    public function test_cannot_remove_slug(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create();

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('slug', '')
            ->call('update');

        $response->assertHasErrors('slug');
    }

    public function test_cannot_take_used_slug(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);
        Project::factory()->create(['slug' => 'test-slug2']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('slug', 'test-slug2')
            ->call('update');

        $response->assertHasErrors('slug');
    }

    public function test_can_change_other_properties_whilst_keeping_slug(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('title', 'Test title')
            ->call('update');

        $response->assertHasNoErrors();
    }

    public function test_cannot_remove_tools(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('tools', '')
            ->call('update');

        $response->assertHasErrors('tools');
    }

    public function test_cannot_change_to_invalid_cover_image(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('coverImage', UploadedFile::fake()->create('test-img.txt'))
            ->call('update');

        $response->assertHasErrors('coverImage');
    }

    public function test_cannot_remove_cover_image_filename(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('coverImageFilename', '')
            ->call('update');

        $response->assertHasErrors('coverImageFilename');
    }

    public function test_cannot_take_used_cover_image_filename(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['cover_img_filename' => 'test-img.png', 'slug' => 'test-slug']);
        Project::factory()->create(['cover_img_filename' => 'test-img2.png', 'slug' => 'test-slug2']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('coverImageFilename', 'test-img2.png')
            ->call('update');

        $response->assertHasErrors('coverImageFilename');
    }

    public function test_can_change_other_properties_whilst_keeping_cover_image_filename(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('title', 'Test title')
            ->call('update');

        $response->assertHasNoErrors();
    }

    public function test_cannot_remove_summary(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('summary', '')
            ->call('update');

        $response->assertHasErrors('summary');
    }

    public function test_cannot_change_to_invalid_repo_link(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['slug' => 'test-slug']);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('repoLink', 'https://invalid link/')
            ->call('update');

        $response->assertHasErrors('repoLink');
    }

    public function test_project_edit_modifies_database_row(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'tools' => 'Test languages',
            'cover_img_filename' => 'test-img.png',
            'summary' => 'Test Summary',
            'repo_link' => 'https://test.link/',
            'standout' => true
        ]);

        $response = Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('title', 'New test Title')
            ->set('slug', 'new-test-slug')
            ->set('tools', 'New test languages')
            ->set('coverImageFilename', 'new-test-img.png')
            ->set('summary', 'New test summary')
            ->set('repoLink', 'https://test.link/new')
            ->set('standout', false)
            ->call('update');

        $response->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => 'New test Title',
            'slug' => 'new-test-slug',
            'tools' => 'New test languages',
            'cover_img_filename' => 'new-test-img.png',
            'summary' => 'New test summary',
            'repo_link' => 'https://test.link/new',
            'standout' => false
        ]);
    }

    public function test_repo_link_removed_when_open_source_unchecked(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'tools' => 'Test languages',
            'cover_img_filename' => 'test-img.png',
            'summary' => 'Test Summary',
            'repo_link' => 'https://test.link/',
            'standout' => false
        ]);

        Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('openSource', false)
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'tools' => 'Test languages',
            'cover_img_filename' => 'test-img.png',
            'summary' => 'Test Summary',
            'repo_link' => null,
            'standout' => false
        ]);
    }

    public function test_cover_image_is_written_to_disk(): void
    {
        Storage::fake('portfolio');
        $this->actingAsAdmin();
        $project = Project::factory()->create(['cover_img_filename' => 'test-img.png']);

        Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('coverImage', UploadedFile::fake()->image('test-img.png'))
            ->call('update');

        Storage::disk('portfolio')->assertExists('cover-images/test-img.png');
    }

    public function test_renaming_cover_image(): void
    {
        Storage::fake('portfolio');
        $this->actingAsAdmin();
        $project = Project::factory()->create(['cover_img_filename' => 'test-img.png']);
        Project::writeCoverImage('test-img.png', UploadedFile::fake()->image('test-img.png'));

        Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('coverImageFilename', 'new-test-img.png')
            ->call('update');

        Storage::disk('portfolio')->assertMissing('cover-images/test-img.png');
        Storage::disk('portfolio')->assertExists('cover-images/new-test-img.png');
    }

    public function test_cover_image_is_renamed_and_written_to_disk(): void
    {
        Storage::fake('portfolio');
        $this->actingAsAdmin();
        $project = Project::factory()->create(['cover_img_filename' => 'test-img.png']);
        Project::writeCoverImage('test-img.png', UploadedFile::fake()->image('test-img.png'));
        $newImage = UploadedFile::fake()->image('test-img.png');

        Livewire::test(EditProject::class, ['slug' => $project->slug])
            ->set('coverImageFilename', 'new-test-img.png')
            ->set('coverImage', $newImage)
            ->call('update');

        Storage::disk('portfolio')->assertMissing('cover-images/test-img.png');
        $this->assertEquals($newImage->get(), Storage::disk('portfolio')->get('cover-images/new-test-img.png'));
    }

}
