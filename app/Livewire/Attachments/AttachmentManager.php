<?php

namespace App\Livewire\Attachments;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class AttachmentManager extends Component
{
    use WithFileUploads;

    public $subheading, $path, $attachmentNames, $attachmentName, $attachment, $attachmentWriter;

    protected $listeners = [
        'editAttachment' => 'edit',
        'deleteAttachment' => 'delete',
        'uploadAttachments' => 'upload'
    ];

    public function mount(): void
    {
        $this->refresh();
    }

    public function add(): void
    {
        if ($this->attachmentWriter->has($this->attachmentName)) {
            $this->addError('attachmentName', 'Name is taken');
            return;
        }
        if (!$this->attachment) {
            $this->addError('attachment', 'File is null');
            return;
        }
        $this->attachmentWriter->add($this->attachmentName, $this->attachment);
        $this->attachmentName = '';
        $this->attachment = '';
        $this->refresh();
    }

    public function edit(string $oldName, string $newName, string|null $serializedFile): void
    {
        if (!$this->attachmentWriter->has($oldName)) {
            session()->flash('error', "Could not find attachment '$oldName'");
            return;
        }
        if ($serializedFile) {
            $file = TemporaryUploadedFile::unserializeFromLivewireRequest($serializedFile);
            if ($this->attachmentWriter->edit($oldName, $newName, $file)) {
                session()->flash('success', "Attachment '$newName' has been updated");
            } else {
                session()->flash('error', "Could not update attachment '$oldName'");
            }
        } else {
            if ($this->attachmentWriter->rename($oldName, $newName)) {
                session()->flash('success', "Attachment '$newName' has been updated");
            } else {
                session()->flash('error', "Could not update attachment '$oldName'");
            }
        }
        $this->refresh();
    }

    public function delete(string $name): void
    {
        if (!$this->attachmentWriter->has($name)) {
            session()->flash('error', "Could not find attachment '$name'");
            return;
        }
        if ($this->attachmentWriter->remove($name)) {
            session()->flash('success', "Attachment '$name' has been deleted");
        } else {
            session()->flash('error', "Could not delete attachment '$name'");
        }
        $this->refresh();
    }

    private function refresh(): void
    {
        $this->attachmentNames = $this->attachmentWriter->getNames();
    }

    public function upload(string $disk, string $path): void
    {
        $this->attachmentWriter->upload($disk, $path);
    }

}
