<?php

namespace App\Modules\Images;

use App\Modules\Images\Providers\ImageProviderInterface;

class ImagePathGenerator
{
    public static function generatePath(string $path, int $width, int $height): string
    {
        return app(ImageProviderInterface::class)
            ->generatePath($path, $width, $height);
    }

    public static function generateFullPath(string $path, int $width = null, int $height = null): string
    {
        return app(ImageProviderInterface::class)
            ->generateFullPath($path, $width, $height);
    }
}
