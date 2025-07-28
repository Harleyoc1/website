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
        $this->validate();
        try {
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
            $this->redirectRoute('management.blog.index');
        } catch (\Throwable $th) {
            session()->flash('error', 'Server error writing post to database');
        }
    }

}
