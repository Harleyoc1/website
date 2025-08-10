<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class EditPost extends Component
{
    public $post, $title, $slug, $summary, $content;

    protected $rules = [
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'max:255', 'unique:posts'],
        'summary' => ['required'],
        'content' => ['required']
    ];

    public function mount(string $slug): void
    {
        $this->post = Post::all()->where('slug', $slug)->first();
        if (!isset($this->post)) {
            abort(404);
        }
        $this->title = $this->post->title;
        $this->slug = $this->post->slug;
        $this->summary = $this->post->summary;
        $this->content = $this->post->readContent() ?? "Unable to read content";
    }

    public function update(): void
    {
        $this->authorize('update', $this->post);
        $this->validate($this->rules());
        try {
            $this->post->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'summary' => $this->summary,
            ]);

            if (!$this->post->writeContent($this->content)) {
                session()->flash('error', 'Server error writing post content to file');
            } else {
                session()->flash('success', 'Post edited successfully');
            }
            $this->redirectRoute('management.blog.index');
        } catch (Throwable $th) {
            session()->flash('error', 'Server error writing post to database');
        }
    }

    private function rules()
    {
        $rules = $this->rules;
        $rules['slug'] = [
            'required',
            'max:255',
            Rule::unique('posts', 'slug')->ignore($this->post->id)
        ];
        return $rules;
    }

}
