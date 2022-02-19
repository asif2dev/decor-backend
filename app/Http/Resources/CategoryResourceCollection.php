<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class CategoryResourceCollection extends ResourceCollection
{
    public function toArray($request): Arrayable|JsonSerializable
    {
        return $this->resource->map(
            fn ($resource) => new CategoryResource($resource)
        );
    }
}
