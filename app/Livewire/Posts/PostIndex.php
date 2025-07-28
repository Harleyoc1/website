<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class PostIndex extends Component
{
    public $posts;

    public function mount(): void
    {
        $this->posts = Post::all();
    }

}
