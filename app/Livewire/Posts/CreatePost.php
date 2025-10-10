<?php

namespace App\Livewire\Posts;

use App\Livewire\Attachments\AttachmentManager;
use App\Models\Post;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Title('Create Blog Post')]
class CreatePost extends Component
{

    public $title, $slug, $summary, $content, $id;

    protected $rules = [
        'title' => ['required', 'max:255'],
        'slug' => ['required', 'max:255', 'unique:posts'],
        'summary' => ['required'],
        'content' => ['required']
    ];

    public function mount(): void
    {
        $this->id = Post::max('id') + 1;
    }

    public function store(): void
    {
        $this->authorize('create', Post::class);
        $this->validate();
        try {
            $post = Post::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'summary' => $this->summary,
            ]);

            if (!$post->writeContent($this->content)) {
                $this->redirectRoute('management.blog.index');
                session()->flash('error', 'Server error writing post content to file');
            } else {
                $this->dispatch('uploadAttachments', 'blog', $post->getAttachmentsPath())
                    ->to(AttachmentManager::class);
                $this->redirectRoute('management.blog.index');
                session()->flash('success', 'Post created successfully');
            }
        } catch (Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

}
