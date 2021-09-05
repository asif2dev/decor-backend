<?php

namespace App\Http\Resources;

use App\Models\ProjectImage;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectImagesResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): Arrayable
    {
        return $this->resource->map(
            function (ProjectImage $image) {
                return ['id' => $image->id, 'path' => $image->path];
            }
        );
    }
}
