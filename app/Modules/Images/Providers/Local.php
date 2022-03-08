<?php

namespace App\Modules\Images\Providers;

class Local implements ImageProviderInterface
{

    public function generatePath(string $path, int $width, int $height): string
    {
        return $path;
    }
}
