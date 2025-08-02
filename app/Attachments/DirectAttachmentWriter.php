<?php

namespace App\Attachments;

use Illuminate\Support\Facades\Storage;

class DirectAttachmentWriter implements AttachmentWriter
{

    private string $disk;
    private string $path;
    private array $attachmentNames;

    public function __construct(string $disk, string $path)
    {
        $this->disk = $disk;
        $this->path = $path;
        $this->attachmentNames = array_map(function($filepath) {
            return basename($filepath);
        }, Storage::disk($disk)->files($this->path));
    }

    public function add(string $name, $file): bool
    {
        if ($file->storeAs($this->path, $name, 'blog')) {
            $this->attachmentNames[] = $name;
            return true;
        }
        return false;
    }

    public function edit(string $oldName, string $newName, $file): bool
    {
        if (!in_array($oldName, $this->attachmentNames) || in_array($newName, $this->attachmentNames)) {
            return false;
        }
        // If name changed, delete old file
        if ($newName !== $oldName) {
            if (!$this->remove($oldName)) {
                return false;
            }
        }
        // Store new file contents
        if ($file->storeAs($this->path, $newName, 'blog')) {
            if ($newName !== $oldName) {
                $this->attachmentNames[] = $newName;
            }
            return true;
        }
        return false;
    }

    public function rename(string $oldName, string $newName): bool
    {
        if (!in_array($oldName, $this->attachmentNames) || in_array($newName, $this->attachmentNames)) {
            return false;
        }
        if ($oldName == $newName) {
            return true;
        }
        if (!Storage::disk($this->disk)->move("$this->path/$oldName", "$this->path/$newName")) {
            return false;
        }
        $this->removeName($oldName);
        $this->attachmentNames[] = $newName;
        return true;
    }

    public function remove(string $name): bool
    {
        if (Storage::disk($this->disk)->delete("$this->path/$name")) {
            $this->removeName($name);
            return true;
        }
        return false;
    }

    private function removeName(string $name): void
    {
        $this->attachmentNames = array_diff($this->attachmentNames, [$name]);
    }

    public function has(string $name): bool
    {
        return in_array($name, $this->attachmentNames);
    }

    public function getNames(): array
    {
        return $this->attachmentNames;
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
