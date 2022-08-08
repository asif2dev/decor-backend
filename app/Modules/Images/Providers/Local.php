<?php

namespace App\Modules\Images\Providers;

class Local implements ImageProviderInterface
{

    public function generatePath(string $path, int $width, int $height): string
    {
        return \Arr::last(explode('uploads', $path));
    }

    public function generateFullPath(string $path, ?int $width = 1024, ?int $height = 1024): string
    {
        return sprintf('%s?w-%d,h-%d', $path, $width, $height);
    }
}
