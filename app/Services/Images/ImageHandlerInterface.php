<?php

namespace App\Services\Images;

use Illuminate\Http\UploadedFile;

interface ImageHandlerInterface
{
    public function uploadImage(UploadedFile $file): string;

    public function removeImage(string $url): void;
}
