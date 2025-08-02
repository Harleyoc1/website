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
        if (isset($this->attachments[$name])) {
            return false;
        }
        $this->attachments[$name] = $file;
        return true;
    }

    public function edit(string $oldName, string $newName, $file): bool
    {
        $nameChanged = $newName !== $oldName;
        if (!isset($this->attachments[$oldName]) || $nameChanged && isset($this->attachments[$newName])) {
            return false;
        }
        if ($nameChanged) {
            unset($this->attachments[$oldName]);
        }
        $this->attachments[$newName] = $file;
        return true;
    }

    public function rename(string $oldName, string $newName): bool
    {
        $nameChanged = $newName !== $oldName;
        if (!isset($this->attachments[$oldName]) || $nameChanged && isset($this->attachments[$newName])) {
            return false;
        }
        $file = $this->attachments[$oldName];
        if ($nameChanged) {
            unset($this->attachments[$oldName]);
        }
        $this->attachments[$newName] = $file;
        return true;
    }

    public function remove(string $name): bool
    {
        if (!isset($this->attachments[$name])) {
            return false;
        }
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
            if (!$file->storeAs($path, $name, $disk)) {
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
