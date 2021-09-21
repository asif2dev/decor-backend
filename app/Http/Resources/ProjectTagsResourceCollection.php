<?php

namespace App\Http\Resources;

use App\Models\ProjectTag;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectTagsResourceCollection extends ResourceCollection
{
    public function toArray($request): Arrayable
    {
        return $this->resource->map(
            fn (ProjectTag $projectTag) => ['name' => $projectTag->name, 'id' => $projectTag->id]
        );
    }
}
