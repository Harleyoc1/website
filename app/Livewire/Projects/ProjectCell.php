<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class ProjectCell extends Component
{

    public Project $project;

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->project);
        if (!$this->project->deleteCoverImage()) {
            $this->error("Server error deleting cover image");
            return;
        }
        $title = $this->project->title;
        if (!$this->project->delete()) {
            $this->error('Server error deleting project from database');
            return;
        }
        $this->redirectRoute('management.portfolio.index');
        session()->flash('success', "Project '$title' deleted");
    }

    private function error(string $message)
    {
        $this->redirectRoute('management.blog.index');
        session()->flash('error', $message);
    }

}
