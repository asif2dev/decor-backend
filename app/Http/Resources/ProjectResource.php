<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'images' => new ProjectImagesResourceCollection($this->resource->images),
            'professional' => new ProfessionalResource($this->resource->professional),
            'tags' => $this->getTags()
        ];
    }

    private function getTags(): array
    {
        $tags = [
            ['name' => 'Kitchen'],
            ['name' => 'Living'],
            ['name' => 'Indoor'],
            ['name' => 'Outdoor'],
            ['name' => 'Bed Room'],
            ['name' => 'Modern'],
            ['name' => 'Classic'],
        ];

        $keys = array_rand($tags, 3);

        return [
            $tags[$keys[0]],
            $tags[$keys[1]],
            $tags[$keys[2]],
        ];
    }
}
