<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class CreatePost extends Component
{

    public $title, $slug, $summary, $content;

    protected $rules = [
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'max:255', 'unique:posts'],
        'summary' => ['required'],
        'content' => ['required']
    ];

    public function store(): void
    {
        $this->authorize('create', Post::class);
        $this->validate();
        $post = Post::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
        ]);

        if (!$post->writeContent($this->content)) {
            session()->flash('error', 'Server error writing post content to file');
        } else {
            session()->flash('success', 'Post created successfully');
        }
        $this->dispatch('uploadAttachments', 'blog', $post->getAttachmentsPath());
        $this->redirectRoute('management.blog.index');
    }

}
