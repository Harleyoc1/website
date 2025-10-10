<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Blog')]
#[Layout('components.layouts.public.page')]
class BlogIndex extends Component
{
    public $posts;

    public function mount()
    {
        // Collect the posts, ordered with most recent first
        $this->posts = Post::all()->sortByDesc('created_at');
    }
}
