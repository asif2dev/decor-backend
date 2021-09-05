<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'comment' => $this->resource->comment,
            'rating' => $this->resource->rating,
            'user' => new UserResource($this->resource->user),
            'createdAt' => $this->resource->created_at->diffForHumans()
        ];
    }
}
