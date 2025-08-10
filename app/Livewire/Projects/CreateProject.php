<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class CreateProject extends Component
{
    use WithFileUploads;

    public string $title, $slug, $tools, $summary, $coverImageFilename, $repoLink;
    public $coverImage;
    public bool $standout;

    protected $rules = [
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'unique:projects', 'max:255'],
        'tools' => ['required', 'max:255'],
        'coverImage' => ['required', 'image', 'max:1024'],
        'coverImageFilename' => ['required', 'unique:projects,cover_img_filename', 'max:255'],
        'summary' => ['required', 'max:255'],
        'repoLink' => ['required', 'max:255', 'url']
    ];

    public function store(): void
    {
        $this->authorize('create', Project::class);
        $this->validate();
        try {
            Project::writeCoverImage($this->coverImageFilename, $this->coverImage);
            Project::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'tools' => $this->tools,
                'summary' => $this->summary,
                'cover_img_filename' => $this->coverImageFilename,
                'repo_link' => $this->repoLink,
                'standout' => $this->standout ?? 0
            ]);
            $this->redirectRoute('management.portfolio.index');
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            $this->redirectRoute('management.portfolio.index');
            session()->flash('error', $th->getMessage());
        }
    }

}
