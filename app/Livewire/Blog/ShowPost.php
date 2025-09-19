<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public.page')]
class ShowPost extends Component
{
    public $post;

    public function mount(string $slug)
    {
        $this->post = Post::all()->where('slug', $slug)->first();
        if (!isset($this->post)) {
            abort(404);
        }
    }
}
