<?php

namespace App\Http\Resources;

use App\Models\ProjectImage;
use App\Models\User;
use App\Modules\Images\ProjectImage as ProjectImagePath;
use App\Modules\Images\ProjectThumb;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ProjectImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request = null)
    {
        return [
            'id' => $this->resource->id,
            'slug' => $this->resource->slug,
            'viewsCount' => $this->resource->views_count,
            'project_id' => $this->resource->project_id,
            'src' => new ProjectImagePath($this->resource->path),
            'thumbnail' => new ProjectThumb($this->resource->path),
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'palette' => $this->resource->palette,
            'space_id' => $this->resource->space_id,
            'design_type_id' =>$this->resource->design_type_id,
            'space' => $this->resource->space ? new SpaceResource($this->resource->space): null,
            'designType' => $this->resource->designType ? new DesignTypeResource($this->resource->designType) : null,
            'professional' => new ProfessionalResource($this->resource->professional)
        ];
    }
}
