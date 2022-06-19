<?php

namespace App\Http\Resources;

use App\Models\Space;
use App\Modules\Images\ProjectImage as ProjectImagePath;
use App\Modules\Images\ProjectThumb;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SpaceResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
