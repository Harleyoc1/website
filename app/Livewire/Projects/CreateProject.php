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

    public string $title, $slug, $tools, $summary, $coverImageFilename;
    public string|null $repoLink = null;
    public $coverImage;
    public bool $openSource = true, $standout;

    protected $rules = [
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'unique:projects', 'max:255'],
        'tools' => ['required', 'max:255'],
        'coverImage' => ['required', 'image', 'max:1024'],
        'coverImageFilename' => ['required', 'unique:projects,cover_img_filename', 'max:255'],
        'summary' => ['required'],
        'repoLink' => ['nullable', 'max:255', 'url']
    ];

    public function store(): void
    {
        $this->authorize('create', Project::class);
        $this->validate();
        try {
            if (!$this->openSource) {
                $this->repoLink = null;
            }
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
            session()->flash('error', $th->getMessage());
        }
    }

}
