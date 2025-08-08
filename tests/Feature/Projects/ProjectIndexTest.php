<?php

namespace Tests\Feature\Projects;

use App\Livewire\Projects\ProjectCell;
use App\Livewire\Projects\ProjectIndex;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/management/portfolio')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_page(): void
    {
        $this->actingAsUser();

        $this->get('/management/portfolio')->assertStatus(403);
    }

    public function test_admin_users_can_visit_the_page(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/portfolio')->assertStatus(200);
    }

    public function test_page_contains_livewire_component(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/portfolio')->assertSeeLivewire(ProjectIndex::class);
    }

    public function test_projects_passed_to_view(): void
    {
        $this->actingAsAdmin();
        Project::factory()->count(3)->create();

        Livewire::test(ProjectIndex::class)
            ->assertViewHas('projects', function ($projects) {
                return count($projects) == 3;
            });
    }

    public function test_page_doesnt_contain_cell_component_when_no_projects(): void
    {
        $this->actingAsAdmin();

        $this->get('/management/portfolio')->assertDontSeeLivewire(ProjectCell::class);
    }

    public function test_page_contains_cell_component_when_projects(): void
    {
        $this->actingAsAdmin();
        Project::factory()->create();

        $this->get('/management/portfolio')->assertSeeLivewire(ProjectCell::class);
    }

    public function test_deletion_updates_view(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create();

        Livewire::test(ProjectCell::class, ['project' => $project])
            ->call('delete');

        Livewire::test(ProjectIndex::class)->assertDontSeeLivewire(ProjectCell::class);
    }

}
