<?php

use Illuminate\Support\Facades\Route;

function upload_to_maven($file, $path) {
    $upload = fopen($path, 'w');
    if (!$upload || !fwrite($upload, stream_get_contents($file))) {
        return false;
    }
    fclose($upload);
    return true;
}

Route::put('maven/{path}', function ($path) {
    if (!authenticate_http_user()) {
        return response('Unauthorized', 401);
    }
    // Get the file from the put request
    $file = fopen('php://input', 'r');
    if (!$file) {
        return response('Error reading file', 500);
    }
    $path = storage_path("maven/$path");
    // Make relevant directories if they do not already exist
    if (!is_dir($path)) {
        mkdir(substr($path, 0, strrpos($path, '/') + 1), 0755, true);
    }
    if (!upload_to_maven($file, $path)) {
        fclose($file);
        return response('Error uploading file', 500);
    }
    fclose($file);
    return response('File successfully uploaded', 200);
})->where('path', '.*');

