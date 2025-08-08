<?php

namespace Tests\Feature\Projects;

use App\Livewire\Projects\ProjectCell;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectCellTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_delete_a_project(): void
    {
        $project = Project::factory()->create();

        Livewire::test(ProjectCell::class, ['project' => $project])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_non_admin_users_cannot_delete_a_project(): void
    {
        $project = Project::factory()->create();

        Livewire::test(ProjectCell::class, ['project' => $project])
            ->call('delete')
            ->assertStatus(403);
    }

    public function test_deleting_project_removes_the_database_row(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create();

        Livewire::test(ProjectCell::class, ['project' => $project])
            ->call('delete');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_deleting_project_removes_cover_image(): void
    {
        Storage::fake('portfolio');
        $this->actingAsAdmin();
        $project = Project::factory()->create(['cover_img_filename' => 'cover.jpg']);
        Project::writeCoverImage('cover.jpg', UploadedFile::fake()->image('cover.jpg'));

        Livewire::test(ProjectCell::class, ['project' => $project])
            ->call('delete');

        Storage::disk('portfolio')->assertMissing('cover-images/cover.jpg');
    }

}
