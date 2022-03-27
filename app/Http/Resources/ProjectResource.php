<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectResource extends JsonResource
{
    public function __construct($resource, private bool $loadProfessional = true)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        $result =  [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'images' => new ProjectImagesResourceCollection($this->resource->images),
            'tags' => $this->getTags()
        ];

        if ($this->loadProfessional) {
            logger()->info('professional id: ', ['prof is: ' => $this->resource->professional]);

            $result['professional'] = new ProfessionalResource($this->resource->professional);
        }

        logger()->info('professional: ' , ['prof' => $result]);

        return $result;
    }

    private function getTags(): TagsResourceCollection
    {
        return new TagsResourceCollection($this->resource->tags);
    }
}
