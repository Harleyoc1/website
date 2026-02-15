<?php

namespace App\Livewire\Maven;

use App\Maven\MavenFile;
use DirectoryIterator;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DirectoryIndex extends Component
{
    public bool $isRoot;
    public string $path;
    public $files = [];

    public function mount(?string $path = '')
    {
        $this->isRoot = $path == '';
        $this->path = $path;
        if (!Storage::disk('maven')->exists($path)) {
            abort(404);
        }
        if (!Storage::disk('maven')->directoryExists($path)) {
            return redirect(route('maven.download', substr($path, 1)));
        }
        $fullPath = Storage::disk('maven')->path($path);
        foreach (new DirectoryIterator($fullPath) as $file) {
            if ($file->isDot()) continue;
            $path = $file->getRealPath();
            $this->files[] = MavenFile::fromPath($path);
        }
    }

    public function render()
    {
        return view('livewire.maven.directory-index')
            ->layout('components.layouts.public.maven-header')
            ->title($this->isRoot ? "Index | Maven" : "Index of $this->path | Maven");
    }
}
