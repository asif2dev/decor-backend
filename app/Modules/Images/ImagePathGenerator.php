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
}
