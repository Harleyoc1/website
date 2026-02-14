<?php

use App\Livewire\Maven\DirectoryIndex;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

function upload_to_maven($file, $path) {
    $upload = fopen($path, 'w');
    if (!$upload || !fwrite($upload, stream_get_contents($file))) {
        return false;
    }
    fclose($upload);
    return true;
}

Route::get('maven/download/{path}', function ($path) {
    if (!Storage::fileExists("maven/$path")) {
        return response('File not found', 404);
    }
    return Storage::download("maven/$path");
})->where('path', '.*')->name('maven.download');

// redirects to download route if path points to file
Route::get('maven{path?}', DirectoryIndex::class)
    ->where('path', '.*')
    ->name('maven.directory-index');

// route used by publishing plugin to upload maven files
Route::put('maven/{path}', function ($path) {
    $user = authenticated_http_user();
    if (!$user) {
        return response('Unauthorized', 401);
    }
    if (!$user->isMavenEditor()) {
        return response('Forbidden', 403);
    }
    // Get the file from the put request
    $file = fopen('php://input', 'r');
    if (!$file) {
        return response('Error reading file', 500);
    }
    $path = Storage::path("maven/$path");
    // Make relevant directories if they do not already exist
    if (!is_dir(dirname($path))) {
        mkdir(substr($path, 0, strrpos($path, '/') + 1), 0755, true);
    }
    if (!upload_to_maven($file, $path)) {
        fclose($file);
        return response('Error uploading file', 500);
    }
    fclose($file);
    return response('File successfully uploaded', 200);
})->where('path', '.*');

