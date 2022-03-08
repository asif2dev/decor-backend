<?php

namespace App\Modules\Images;

use JsonSerializable;

class Image implements JsonSerializable
{
    protected int $width;
    protected int $height;

    public function __construct(protected string $path)
    {
    }

    public function jsonSerialize()
    {
        return ImagePathGenerator::generatePath($this->path, $this->width, $this->height);
    }
}
