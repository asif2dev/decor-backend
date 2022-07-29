<?php

namespace App\Http\Resources;

use App\Models\ProjectImage;
use App\Modules\Images\ProjectImage as ProjectImagePath;
use App\Modules\Images\ProjectThumb;
use Illuminate\Http\Request;
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
    public function toArray($request)
    {
        /** @var User $user */
        $user = $request->user();
        $images = $user ? $user->favoriteProjectImages()->get() : collect();

        return [
            'id' => $this->resource->id,
            'slug' => $this->resource->slug,
            'project_id' => $this->resource->project_id,
            'src' => new ProjectImagePath($this->resource->path),
            'thumbnail' => new ProjectThumb($this->resource->path),
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'palette' => $this->resource->palette,
            'space_id' => $this->resource->space_id,
            'design_type_id' =>$this->resource->design_type_id,
            'space' => new SpaceResource($this->resource->space),
            'designType' => new DesignTypeResource($this->resource->designType),
            'professional' => new ProfessionalResource($this->resource->professional),
            'isFavorited' => $user && $this->isFavorited($images, $this->resource),
        ];
    }

    private function isFavorited(Collection $images, ProjectImage $projectImage): bool
    {
        if ($images->isEmpty()) {
            return false;
        }

        return $images->filter(fn(ProjectImage $image) => $image->id === $projectImage->id)
                ->count() !== 0;
    }
}
