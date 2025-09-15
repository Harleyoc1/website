<?php

namespace Tests\Feature\Portfolio;

use App\Livewire\Portfolio\PortfolioIndex;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response(): void
    {
        $this->get('/portfolio')->assertSuccessful();
    }

    public function test_contains_portfolio_livewire_component(): void
    {
        $this->get('/portfolio')->assertSeeLivewire(PortfolioIndex::class);
    }

    public function test_can_see_projects(): void
    {
        Project::factory()->create(['title' => 'Test title']);
        Project::factory()->create(['title' => 'Test title 2']);

        $this->get('/portfolio')
            ->assertSee('Test title')
            ->assertSee('Test title 2');
    }

    public function test_can_see_all_project_info(): void
    {
        $project = Project::factory()->create([
            'title' => 'Test title',
            'tools' => 'Test tools',
            'cover_img_filename' => 'cover-image.jpg',
            'summary' => 'Test summary',
            'repo_link' => 'https://test.link/'
        ]);

        $this->get('/portfolio')
            ->assertSee('Test title')
            ->assertSee('Test tools')
            ->assertSeeHtml('src="' . $project->getCoverImagePath() . '"')
            ->assertSee('Test summary')
            ->assertSeeHtml('href="https://test.link/"');
    }

    public function test_projects_shown_in_order(): void
    {
        // Defaults to insertion order
        Project::factory()->create(['title' => 'Test title']);
        Project::factory()->create(['title' => 'Test title 2']);
        Project::factory()->create(['title' => 'Test title 3']);

        $this->get('/portfolio')
            ->assertSeeInOrder(['Test title', 'Test title 2', 'Test title 3']);
    }

    public function test_guests_cannot_see_admin_panel(): void
    {
        $this->get('/portfolio')->assertDontSeeHtml('id="admin-panel"');
    }

    public function test_non_admin_users_cannot_see_admin_panel(): void
    {
        $this->actingAsUser();

        $this->get('/portfolio')->assertDontSeeHtml('id="admin-panel"');
    }

    public function test_admins_can_see_admin_panel(): void
    {
        $this->actingAsAdmin();

        $this->get('/portfolio')->assertSeeHtml('id="admin-panel"');
    }
}
