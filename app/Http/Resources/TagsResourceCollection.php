<?php

namespace App\Http\Resources;

use App\Models\ProjectTag;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagsResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->resource->map(
            fn (Tag $tag) => ['name' => $tag->name, 'id' => $tag->id]
        );
    }
}
