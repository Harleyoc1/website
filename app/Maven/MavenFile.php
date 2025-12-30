<?php

namespace App\Maven;

use Carbon\Carbon;
use Livewire\Wireable;

class MavenFile implements Wireable
{

    private string $name;
    private Carbon $modified;
    private int $size;
    private string $hash;
    private bool $isDir;

    private function __construct(string $name, Carbon $modified, int $size, string $hash, bool $isDir = false)
    {
        $this->name = $name;
        $this->modified = $modified;
        $this->size = $size;
        $this->hash = $hash;
        $this->isDir = $isDir;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModified(): Carbon
    {
        return $this->modified;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getSizeFormatted(): string
    {
        if ($this->isDir) {
            return '-';
        }
        if ($this->size < 1024) {
            return $this->size . 'B';
        }
        if ($this->size < 1024 * 1024) {
            return round($this->size / 1024) . 'KB';
        }
        if ($this->size < 1024 * 1024 * 1024) {
            return round($this->size / 1024 / 1024) . 'MB';
        }
        return round($this->size / 1024 / 1024 / 1024) . 'GB';
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function isDir(): bool
    {
        return $this->isDir;
    }

    public function toLivewire()
    {
        return ['name' => $this->name, 'modified' => $this->modified, 'size' => $this->size, 'hash' => $this->hash];
    }

    public static function fromLivewire($value)
    {
        return new static($value['name'], $value['modified'], $value['size'], $value['hash'], $value['hash'] == '-');
    }

    public static function fromPath(string $path)
    {
        $isDir = is_dir($path);
        return new static(
            basename($path),
            Carbon::createFromTimestamp(filemtime($path)),
            filesize($path),
            $isDir ? '-' : hash_file('sha256', $path),
            $isDir
        );
    }
}
