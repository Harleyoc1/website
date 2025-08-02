<?php

namespace App\Livewire\Attachments;

use App\Attachments\WireableUploadedFileWrapper;
use Livewire\Component;
use Livewire\WithFileUploads;

class AttachmentCell extends Component
{
    use WithFileUploads;

    public $name, $newName, $file, $path, $showEditModal;

    public function mount(string $name)
    {
        $this->name = $name;
        $this->newName = $name;
    }

    public function edit(): void
    {
        $this->dispatch('editAttachment', oldName: $this->name, newName: $this->newName,
            serializedFile: $this->file ? $this->file->serializeForLivewireResponse() : null)
            ->to(AttachmentManager::class);
        $this->name = $this->newName;
        $this->showEditModal = false;
        $this->file = null;
    }

    public function delete(): void
    {
        $this->dispatch('deleteAttachment', name: $this->name)
            ->to(AttachmentManager::class);
    }

}
