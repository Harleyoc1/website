<?php

namespace App\Attachments;

class BufferedAttachmentWriter implements AttachmentWriter
{

    private array $attachments;

    public function __construct(array $attachments = [])
    {
        $this->attachments = $attachments;
    }

    public function add(string $name, $file): bool
    {
        $this->attachments[$name] = $file;
        return true;
    }

    public function edit(string $oldName, string $newName, $file): bool
    {
        if (!isset($this->attachments[$oldName]) || isset($this->attachments[$newName])) {
            return false;
        }
        if ($newName !== $oldName) {
            unset($this->attachments[$oldName]);
        }
        $this->attachments[$newName] = $file;
        return true;
    }

    public function rename(string $oldName, string $newName): bool
    {
        if (!isset($this->attachments[$oldName]) || isset($this->attachments[$newName])) {
            return false;
        }
        $file = $this->attachments[$oldName];
        if ($newName !== $oldName) {
            unset($this->attachments[$oldName]);
        }
        $this->attachments[$newName] = $file;
        return true;
    }

    public function remove(string $name): bool
    {
        unset($this->attachments[$name]);
        return true;
    }

    public function has(string $name): bool
    {
        return isset($this->attachments[$name]);
    }

    public function getNames(): array
    {
        return array_keys($this->attachments);
    }

    public function upload(string $disk, string $path): bool
    {
        foreach ($this->attachments as $name => $file) {
            if (!$file->storeAs($path, $name, 'blog')) {
                return false;
            }
        }
        return true;
    }

    public function toLivewire()
    {
        return $this->attachments;
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }

}
