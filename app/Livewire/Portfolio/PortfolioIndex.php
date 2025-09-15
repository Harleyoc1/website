<?php

namespace App\Livewire\Portfolio;

use App\Models\Project;
use Livewire\Component;

class PortfolioIndex extends Component
{
    public $projects;

    public function render()
    {
        return view('livewire.portfolio.portfolio-index')->layout('components.layouts.public.page');
    }

    public function mount(): void
    {
        // Collect the project, ordered with most recent first
        $this->projects = Project::all()->sortBy('order_index');
    }
}
