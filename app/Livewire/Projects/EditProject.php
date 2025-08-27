<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class EditProject extends Component
{
    use WithFileUploads;

    public Project $project;
    public string $title, $slug, $tools, $summary, $coverImageFilename, $repoLink;
    public $coverImage;
    public bool $standout;

    protected $rules = [
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'unique:projects', 'max:255'],
        'tools' => ['required', 'max:255'],
        'coverImage' => ['nullable', 'image', 'max:1024'],
        'coverImageFilename' => ['required', 'unique:projects,cover_img_filename', 'max:255'],
        'summary' => ['required', 'max:255'],
        'repoLink' => ['required', 'max:255', 'url']
    ];

    public function mount(string $slug): void
    {
        $project = Project::all()->where('slug', $slug)->first();
        if (!isset($project)) {
            abort(404);
        }
        $this->project = $project;
        $this->title = $this->project->title;
        $this->slug = $this->project->slug;
        $this->tools = $this->project->tools;
        $this->summary = $this->project->summary;
        $this->coverImageFilename = $this->project->cover_img_filename;
        $this->repoLink = $this->project->repo_link;
        $this->standout = $this->project->standout == '1';
    }

    public function update(): void
    {
        $this->authorize('update', $this->project);
        $this->validate($this->rules());
        try {
            $this->project->updateCoverImage($this->coverImageFilename, $this->coverImage);
            $this->project->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'tools' => $this->tools,
                'summary' => $this->summary,
                'cover_img_filename' => $this->coverImageFilename,
                'repo_link' => $this->repoLink,
                'standout' => $this->standout
            ]);
            session()->flash('success', 'Project edited successfully');
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('error', $th->getMessage());
        }
    }

    private function rules()
    {
        $rules = $this->rules;
        $rules['slug'] = [
            'required',
            'max:255',
            Rule::unique('projects', 'slug')->ignore($this->project->id)
        ];
        $rules['coverImageFilename'] = [
            'required',
            'max:255',
            Rule::unique('projects', 'cover_img_filename')->ignore($this->project->id)
        ];
        return $rules;
    }

}
