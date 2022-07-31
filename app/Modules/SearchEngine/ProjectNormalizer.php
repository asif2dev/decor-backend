<?php

namespace App\Modules\SearchEngine;

use App\Http\Resources\ProjectImageResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectImage;

class ProjectNormalizer
{
    public static function toSearchableArray(Project $project): array
    {
        $project = new ProjectResource($project, true);

        return $project->toArray();
    }
}
