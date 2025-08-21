<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class BlogIndex extends Component
{
    public $posts;

    public function render()
    {
        return view('livewire.blog.blog-index')->layout('components.layouts.public.page');
    }

    public function mount()
    {
        // Collect the posts, ordered with most recent first
        $this->posts = Post::all()->sortByDesc('created_at');
    }
}
