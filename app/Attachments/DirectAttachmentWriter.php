<?php

namespace App\Attachments;

use Illuminate\Support\Facades\Storage;

class DirectAttachmentWriter implements AttachmentWriter
{

    private array $attachmentNames = [];

    public function __construct(
        private string $disk,
        private string $path
    ) {
        foreach (Storage::disk($disk)->files($this->path) as $file) {
            $this->attachmentNames[basename($file)] = true;
        }
    }

    public function add(string $name, $file): bool
    {
        if ($this->has($name)) {
            return false;
        }
        if ($file->storeAs($this->path, $name, $this->disk)) {
            $this->attachmentNames[$name] = true;
            return true;
        }
        return false;
    }

    public function edit(string $oldName, string $newName, $file): bool
    {
        $nameChanged = $newName !== $oldName;
        if (!$this->has($oldName) || $nameChanged && $this->has($newName)) {
            return false;
        }
        if ($nameChanged && !$this->remove($oldName)) {
            return false;
        }
        // Store new file contents
        if ($file->storeAs($this->path, $newName, $this->disk)) {
            if ($newName !== $oldName) {
                $this->attachmentNames[$newName] = true;
            }
            return true;
        }
        return false;
    }

    public function rename(string $oldName, string $newName): bool
    {
        $nameChanged = $newName !== $oldName;
        if (!$this->has($oldName) || $nameChanged && $this->has($newName)) {
            return false;
        }
        if (!$nameChanged) {
            return true;
        }
        if (!Storage::disk($this->disk)->move("$this->path/$oldName", "$this->path/$newName")) {
            return false;
        }
        unset($this->attachmentNames[$oldName]);
        $this->attachmentNames[$newName] = true;
        return true;
    }

    public function remove(string $name): bool
    {
        if (!$this->has($name)) {
            return false;
        }
        if (Storage::disk($this->disk)->delete("$this->path/$name")) {
            unset($this->attachmentNames[$name]);
            return true;
        }
        return false;
    }

    public function has(string $name): bool
    {
        return isset($this->attachmentNames[$name]);
    }

    public function getNames(): array
    {
        return array_keys($this->attachmentNames);
    }

    public function upload(string $disk, string $path): bool
    {
        return true;
    }

    public function toLivewire()
    {
        return ['disk' => $this->disk, 'path' => $this->path];
    }

    public static function fromLivewire($value)
    {
        return new static($value['disk'], $value['path']);
    }

}
