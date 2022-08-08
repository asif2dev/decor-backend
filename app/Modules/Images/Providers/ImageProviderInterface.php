<?php

namespace App\Modules\Images\Providers;

interface ImageProviderInterface
{
    public function generatePath(string $path, int $width, int $height): string;

    public function generateFullPath(string $path, int $width = 1024, int $height = 1024): string;
}
