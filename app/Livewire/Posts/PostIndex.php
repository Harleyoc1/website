<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class PostIndex extends Component
{
    public $posts;

    public function render()
    {
        $this->posts = Post::all();
        return view('livewire.posts.index');
    }

}
