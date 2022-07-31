<?php

namespace App\Modules\SearchEngine;

use App\Http\Resources\ProjectImageResource;
use App\Http\Resources\ProjectResource;
use App\Models\ProjectImage;

class ProjectImageNormalizer
{
    public static function toSearchableArray(ProjectImage $projectImage): array
    {
        $projectImage = new ProjectImageResource($projectImage);

        $result = $projectImage->toArray();
        $result['project'] = (new ProjectResource($projectImage->project, false))->toArray();

        return $result;
    }
}
