<?php

namespace App\Http\Resources;

use App\Models\ProjectImage;
use App\Modules\Images\ProjectThumb;
use App\Modules\Images\ProjectImage as ProjectImagePath;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectImagesResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array|Arrayable
    {
        return $this->resource->map(
            function (ProjectImage $image) {
                return [
                    'id' => $image->id,
                    'src' => new ProjectImagePath($image->path),
                    'thumbnail' => new ProjectThumb($image->path),
                    'title' => $image->title,
                    'description' => $image->description,
                    'palette' => $image->palette,
                    'space_id' => $image->space_id,
                    'design_type_id' =>$image->design_type_id,
                    'space' => new SpaceResource($image->space),
                    'designType' => new DesignTypeResource($image->designType),
                    'professional' => new ProfessionalResource($image->professional)
                ];
            }
        );
    }
}
