<?php

use Illuminate\Http\UploadedFile;

function upload_to_maven($file, $path) {
    $upload = fopen($path, 'w');
    $contents = $file instanceof UploadedFile ? $file->getContent() : stream_get_contents($file);
    if (!$upload || !fwrite($upload, $contents)) {
        return false;
    }
    fclose($upload);
    return true;
}
