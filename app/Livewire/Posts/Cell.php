<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Cell extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function delete(): void
    {
        $this->post->deleteContent();
        $this->post->delete();
        $this->redirectRoute('management.blog.index');
    }
}
