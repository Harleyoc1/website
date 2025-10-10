<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public.page')]
class Home extends Component
{

    public $projects, $posts;

    public function mount(): void
    {
        $this->projects = Project::where('standout', '1')->get();
        $this->posts = Post::latest()->take(3)->get();
    }

}
