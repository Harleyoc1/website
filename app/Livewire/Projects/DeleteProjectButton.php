<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class DeleteProjectButton extends Component
{
    public Project $project;
    public string|null $redirectTo = null;
    public bool $text = true;

    public function render()
    {
        return view('livewire.projects.delete-project-button');
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
        $this->redirectRoute($this->redirectTo);
        session()->flash('success', "Project '$title' deleted");
    }

    private function error(string $message): void
    {
        $this->redirectRoute($this->redirectTo);
        session()->flash('error', $message);
    }

}
