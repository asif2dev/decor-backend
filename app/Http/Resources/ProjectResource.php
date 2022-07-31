<?php

namespace App\Http\Resources;

use App\Modules\Images\ProjectImage as ProjectImagePath;
use App\Modules\Images\ProjectThumb;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ProjectResource extends JsonResource
{
    public function __construct($resource, private bool $loadProfessional = true)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request = null): array
    {
        $result = [
            'id' => $this->resource->id,
            'slug' => $this->resource->slug,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'images' => $this->getImages($this->resource->images),
            'tags' => $this->getTags()
        ];

        if ($this->loadProfessional) {
            $result['professional'] = new ProfessionalResource($this->resource->professional);
        }

        return $result;
    }

    private function getTags(): TagsResourceCollection
    {
        return new TagsResourceCollection($this->resource->tags);
    }

    private function getImages(Collection $images): array
    {
        $result = [];
        foreach ($images as $image) {
            $result[] = [
                'id' =>  $image->id,
                'title' =>  $image->title,
                'space_id' =>  $image->space_id,
                'design_type_id' =>  $image->design_type_id,
                'palette' =>  $image->palette,
                'description' =>  $image->description,
                'src' => new ProjectImagePath($image->path),
                'slug' => $image->slug,
                'thumbnail' => new ProjectThumb($image->path)
            ];
        }

        return $result;
    }
}
