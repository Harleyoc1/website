<?php

namespace App\Livewire\Portfolio;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public.page')]
class PortfolioIndex extends Component
{
    public $projects;

    public function mount(): void
    {
        // Collect the project, ordered with most recent first
        $this->projects = Project::all()->sortBy('order_index');
    }
}
