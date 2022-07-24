<?php

namespace App\Modules\SearchEngine;

use App\Http\Resources\ProjectImageResource;
use App\Models\ProjectImage;

class ProjectImageNormalizer
{
    public static function toSearchableArray(ProjectImage $projectImage): array
    {
        $projectImage = new ProjectImageResource($projectImage);

        return $projectImage->toArray();
    }
}
