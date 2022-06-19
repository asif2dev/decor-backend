<?php


namespace App\Services\Images;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ProjectImage implements ImageHandlerInterface
{
    const PATH = 'projects';

    public function uploadImage(UploadedFile $file): string
    {
        $path = Storage::putFile( self::PATH, $file);

        return Storage::url($path);
    }

    public function removeImage(string $url): void
    {
        Storage::delete(self::generatePath($url));
    }

    public static function generatePath(string $url): string
    {
        return self::PATH . '/' . Arr::last(explode(self::PATH . '/', $url));
    }
}
