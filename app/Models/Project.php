<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Project extends Model
{
    use HasFactory;

    private static string $PORTFOLIO = 'portfolio';
    private static string $COVER_IMAGES = 'cover-images';

    protected $fillable = [
        'title',
        'slug',
        'tools',
        'cover_img_filename',
        'summary',
        'repo_link',
        'standout',
        'order_index'
    ];

    public function getCoverImagePath(): string
    {
        return '/portfolio-data/' . self::$COVER_IMAGES . '/' . $this->cover_img_filename;
    }

    public function updateCoverImage(string $filename, TemporaryUploadedFile|null $file): bool
    {
        $success = false;
        $nameChanged = $this->cover_img_filename != $filename;
        if ($file) {
            // If new name, remove old file before writing
            if ($nameChanged) {
                $success = Storage::disk(self::$PORTFOLIO)->delete(
                    self::$COVER_IMAGES . '/' . $this->cover_img_filename
                );
            }
            $success = (!$nameChanged || $success) && $file->storeAs(self::$COVER_IMAGES, $filename, self::$PORTFOLIO);
        } else if ($nameChanged) {
            // If new name but file not set, rename the file
            $success = Storage::disk(self::$PORTFOLIO)->move(
                self::$COVER_IMAGES . '/' . $this->cover_img_filename,
                self::$COVER_IMAGES . '/' . $filename
            );
        }
        return $success;
    }

    public function deleteCoverImage(): bool
    {
        return Storage::disk(self::$PORTFOLIO)->delete(self::$COVER_IMAGES . '/' . $this->cover_img_filename);
    }

    public static function writeCoverImage(string $filename, UploadedFile $file): bool
    {
        return is_string($file->storeAs(self::$COVER_IMAGES, $filename, self::$PORTFOLIO));
    }

}
