<?php

namespace App\Attachments;

use Livewire\Wireable;

interface AttachmentWriter extends Wireable
{

    public function add(string $name, $file): bool;

    public function edit(string $oldName, string $newName, $file): bool;

    public function rename(string $oldName, string $newName): bool;

    public function remove(string $name): bool;

    public function has(string $name): bool;

    public function getNames(): array;

    public function upload(string $disk, string $path): bool;

}
