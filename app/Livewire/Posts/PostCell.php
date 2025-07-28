<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class PostCell extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function delete(): void
    {
        if (!$this->post->deleteContent()) {
            $this->error("Server error writing post content to file");
            return;
        }
        $title = $this->post->title;
        if (!$this->post->delete()) {
            $this->error('Server error writing post to database');
            return;
        }
        $this->redirectRoute('management.blog.index');
        session()->flash('success', "Post '$title' deleted");
    }

    private function error(string $message) {
        $this->redirectRoute('management.blog.index');
        session()->flash('error', $message);
    }
}
