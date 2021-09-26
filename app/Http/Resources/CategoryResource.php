<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     */
    #[ArrayShape([
        'id' => "int",
        'name' => "string",
        'slug' => "string",
        'photo' => "string",
        'description' => "string"
    ])] public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'photo' => $this->resource->photo,
            'description' => $this->resource->description,
        ];
    }
}
