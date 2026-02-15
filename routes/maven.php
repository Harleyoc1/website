<?php

use App\Livewire\Maven\DirectoryIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('maven/download/{path}', function ($path) {
    if (!Storage::disk('maven')->fileExists($path)) {
        return response('File not found', 404);
    }
    return Storage::disk('maven')->download($path);
})->where('path', '.*')->name('maven.download');

// redirects to download route if path points to file
Route::get('maven{path?}', DirectoryIndex::class)
    ->where('path', '.*')
    ->name('maven.directory-index');

// route used by publishing plugin to upload maven files
Route::middleware(['basic_auth', 'maven_editor_auth'])
    ->put('maven/{path}', function (Request $request, $path) {
    // Get the file from the put request
    $file = $request->file('file');
    if (!$file) {
        $file = fopen('php://input', 'r');
        if (!$file) {
            return response('Error reading file', 500);
        }
    }
    $path = Storage::disk('maven')->path($path);
    // Make relevant directories if they do not already exist
    if (!is_dir(dirname($path))) {
        mkdir(substr($path, 0, strrpos($path, '/') + 1), 0755, true);
    }
    if (!upload_to_maven($file, $path)) {
        return response('Error uploading file', 500);
    }
    return response('File successfully uploaded', 200);
})->where('path', '.*');

