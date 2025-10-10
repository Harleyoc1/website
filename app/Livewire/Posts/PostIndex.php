<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Blog')]
class PostIndex extends Component
{
    public $posts;

    public function mount(): void
    {
        $this->posts = Post::all();
    }

}
