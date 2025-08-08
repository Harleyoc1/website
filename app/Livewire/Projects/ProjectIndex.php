<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class ProjectIndex extends Component
{

    public $projects;

    public function mount(): void
    {
        $this->projects = Project::all();
    }

}
