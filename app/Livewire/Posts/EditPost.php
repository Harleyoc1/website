<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EditPost extends Component
{
    public $post, $title, $slug, $summary, $content;

    public function mount(string $slug): void
    {
        $this->post = Post::all()->where('slug', $slug)->first();
        $this->title = $this->post->title;
        $this->slug = $this->post->slug;
        $this->summary = $this->post->summary;
        $this->content = $this->post->readContent() ?? "Unable to read content";
    }

    public function render()
    {
        return view('livewire.posts.create');
    }

    public function store(): void
    {
        $this->validate([
            'title' => ['required', 'max:255'],
            'slug' => ['required', 'max:255', Rule::unique('posts', 'slug')->ignore($this->post->id)],
            'summary' => ['required'],
            'content' => ['required']
        ]);
        try {
            $this->post->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'summary' => $this->summary,
            ]);

            if (!$this->post->writeContent($this->content)) {
                session()->flash('error', 'Server error writing post content to file');
            }

            session()->flash('success', 'Post edited successfully');
            $this->redirectRoute('management.blog.index');
        } catch (\Throwable $th) {
            session()->flash('error', 'Server error writing post to database');
        }
    }

}
