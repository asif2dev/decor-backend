<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

class ProfessionalProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     */
    public function toArray($request): array
    {
        return [
            'uid' => $this->resource->uid,
            'companyName' => $this->resource->company_name,
            'about' => $this->resource->about,
            'category' => new CategoryResource($this->resource->category),
            'phone1' =>  $this->resource->phone1,
            'phone2' =>  $this->resource->phone2,
            'latLng' =>  $this->resource->lat_lng,
            'fullAddress' =>  $this->resource->full_address,
            'projectsCount' =>  $this->resource->projects()->count(),
            'reviewsCount' => $this->resource->reviews()->count(),
            'rating' => (float) ($this->resource->reviews()->avg('rating') ?? 0),
            'reviews' => new ProfessionalReviewResourceCollection($this->resource->reviews),
            'projects' => $this->getProjects()
        ];
    }

    private function getProjects(): Collection
    {
        $projects = collect();
        foreach ($this->resource->projects as $project) {
            $projects->push(new ProjectResource($project, false));
        }

        return $projects;
    }
}
