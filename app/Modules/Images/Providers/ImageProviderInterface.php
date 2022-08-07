<?php

namespace App\Modules\Images\Providers;

interface ImageProviderInterface
{
    public function generatePath(string $path, int $width, int $height): string;

    public function generateFullPath(string $path, int $width = null, int $height = null): string;
}
