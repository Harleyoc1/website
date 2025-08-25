<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Parsedown;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary'
    ];

    public function getAttachmentsPath(): string
    {
        return "$this->id/attachments";
    }

    private function getContentPath(): string
    {
        return "$this->id/content.md";
    }

    private function getContentPathHtml(): string
    {
        return "$this->id/content.html";
    }

    public function readContent(): string|null
    {
        $path = $this->getContentPath();
        return Storage::disk('blog')->get($path);
    }

    public function writeContent(string $content): bool
    {
        $path = $this->getContentPath();
        if (!Storage::disk('blog')->put($path, $content)) {
            return false;
        }
        $html = $this->convertToHtml($content);
        $path = $this->getContentPathHtml();
        return Storage::disk('blog')->put($path, $html);
    }

    public function deleteContent(): bool
    {
        $path = $this->getContentPath();
        $pathHtml = $this->getContentPathHtml();
        return Storage::disk('blog')->delete($path) && Storage::disk('blog')->delete($pathHtml);
    }

    public function readAsHtml(): string|null
    {
        $path = $this->getContentPathHtml();
        if (!Storage::disk('blog')->exists($path)) {
            $html = $this->convertToHtml($this->readContent());
            Storage::disk('blog')->put($path, $html);
            return $html;
        }
        return Storage::disk('blog')->get($path);
    }

    private function convertToHtml(string $content): string {
        return Parsedown::instance()->text($content);
    }

}
