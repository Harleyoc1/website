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
        $this->authorize('delete', $this->post);
        if (!$this->post->deleteContent()) {
            $this->error("Server error deleting post content");
            return;
        }
        $title = $this->post->title;
        if (!$this->post->delete()) {
            $this->error('Server error deleting post from database');
            return;
        }
        $this->redirectRoute('management.blog.index');
        session()->flash('success', "Post '$title' deleted");
    }

    private function error(string $message): void
    {
        $this->redirectRoute('management.blog.index');
        session()->flash('error', $message);
    }
}
