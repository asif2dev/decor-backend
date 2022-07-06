<?php

namespace App\Http\Resources;

use App\Models\Professional;
use App\Modules\Images\ProfessionalLogo;
use App\Modules\Images\ProjectImage as ProjectImagePath;
use App\Modules\Images\ProjectThumb;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
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
    public function toArray($request): array
    {
        $result = [
            'id' => $this->resource->id,
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
                'slug' => $image->slug,
                'thumbnail' => new ProjectThumb($image->path)
            ];
        }

        return $result;
    }
}
