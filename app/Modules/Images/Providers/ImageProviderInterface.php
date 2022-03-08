<?php

namespace App\Modules\Images\Providers;

interface ImageProviderInterface
{
    public function generatePath(string $path, int $width, int $height): string;
}
