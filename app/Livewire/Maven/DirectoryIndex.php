<?php

namespace App\Livewire\Maven;

use Carbon\Carbon;
use DirectoryIterator;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DirectoryIndex extends Component
{
    public string $path;
    public $files = [];

    public function mount(?string $path = '')
    {
        if (!Storage::directoryExists("repositories/maven/$path")) {
            return redirect(route('maven.directory-index', ''));
        }
        $this->path = Storage::path("repositories/maven/$path");
        foreach (new DirectoryIterator($this->path) as $file) {
            if ($file->isDot()) continue;
            $path = $file->getRealPath();
            $fileInfo = ['name' => $file->getFilename(), 'modified' => Carbon::createFromTimestamp(filemtime($path)), 'hash' => is_dir($path) ? '-' : hash_file('sha256', $path)];
            $this->files[] = $fileInfo;
        }
    }

    public function render()
    {
        return view('livewire.maven.directory-index')
            ->layout('components.layouts.public.page')
            ->title("Index of $this->path | Harley O'Connor Maven");
    }
}
