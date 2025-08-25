<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class DeletePostButton extends Component
{
    public Post $post;
    public string|null $redirectTo = null;
    public bool $text = true;

    public function render()
    {
        return view('livewire.posts.delete-post-button');
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
        $this->redirectRoute($this->redirectTo);
        session()->flash('success', "Post '$title' deleted");
    }

    private function error(string $message): void
    {
        $this->redirectRoute($this->redirectTo);
        session()->flash('error', $message);
    }

}
