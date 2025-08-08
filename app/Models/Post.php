<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'summary'];

    public function getAttachmentsPath(): string
    {
        return "$this->id/attachments";
    }

    private function getContentPath(): string
    {
        return "$this->id/content.md";
    }

    public function readContent(): string|null
    {
        $path = $this->getContentPath();
        return Storage::disk('blog')->get($path);
    }

    public function writeContent(string $content): bool
    {
        $path = $this->getContentPath();
        return Storage::disk('blog')->put($path, $content);
    }

    public function deleteContent(): bool
    {
        $path = $this->getContentPath();
        return Storage::disk('blog')->delete($path);
    }

}
