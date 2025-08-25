<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class ShowPost extends Component
{
    public $post;

    public function render()
    {
        return view('livewire.blog.show-post')->layout('components.layouts.public.page');
    }

    public function mount(string $slug)
    {
        $this->post = Post::all()->where('slug', $slug)->first();
        if (!isset($this->post)) {
            abort(404);
        }
    }
}
