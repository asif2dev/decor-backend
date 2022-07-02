<?php

namespace App\Http\Resources;

use App\Models\ProjectImage;
use App\Modules\Images\ProjectThumb;
use App\Modules\Images\ProjectImage as ProjectImagePath;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectImageResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array|Arrayable
    {
        return parent::toArray($request);
    }
}
