<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class AttachmentManager extends Component
{
    use WithFileUploads;

    public $subheading, $attachmentName, $attachment, $attachmentWriter;

    protected $listeners = ['uploadAttachments'];

    public function render()
    {
        return view('livewire.attachment-manager');
    }

    public function addAttachment(): void
    {
        if ($this->attachmentWriter->has($this->attachmentName)) {
            session()->flash('error', 'Attachment already exists');
            return;
        }
        $this->attachmentWriter->add($this->attachmentName, $this->attachment);
        $this->attachmentName = '';
        $this->attachment = '';
    }

    public function uploadAttachments(string $disk, string $path): void
    {
        $this->attachmentWriter->upload($disk, $path);
    }

}
