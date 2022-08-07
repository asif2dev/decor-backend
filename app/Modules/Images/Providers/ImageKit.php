<?php

namespace App\Modules\Images\Providers;

class ImageKit implements ImageProviderInterface
{
    private string $url = 'https://ik.imagekit.io/nostashteeb';

    public function generatePath(string $path, int $width, int $height): string
    {
        if (str_contains($path, '127')) {
            return $path;
        }

        return sprintf(
            '%s?tr=w-%d,h-%d,q-100',
            self::getRootPath($path),
            $width,
            $height
        );
    }

    public static function getRootPath(string $path): string
    {
        [$host, $path] = explode(config('filesystems.disks.gcs.bucket'), $path);

        return $path;
    }

    public function generateFullPath(string $path, int $width = null, int $height = null): string
    {
        if ($width && $height) {
            return sprintf(
                '%s?tr=w-%d,h-%d,q-100',
                $path,
                $width,
                $height
            );
        }

        return $path;
    }
}
