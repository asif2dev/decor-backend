<?php

namespace App\Http\Resources;

use App\Modules\Images\ProfessionalLogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

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
            'logo' => new ProfessionalLogo($this->resource->logo),
            'about' => $this->resource->about,
            'categories' => new CategoryResourceCollection($this->resource->categories),
            'phone1' =>  $this->resource->phone1,
            'phone2' =>  $this->resource->phone2,
            'latLng' =>  $this->resource->lat_lng,
            'fullAddress' =>  $this->resource->full_address,
            'workScope' =>  $this->resource->work_scope,
            'projectsCount' =>  $this->resource->projects()->count(),
            'reviewsCount' => $this->resource->reviews()->count(),
            'rating' => (float) ($this->resource->reviews()->avg('rating') ?? 0),
            'social' => $this->parseSocial($this->resource->social),
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

    private function parseSocial(?array $social = null): array
    {
        $result = [
            'facebook' => null,
            'youtube' => null,
            'instagram' => null,
            'behance' => null,
        ];

        if ($social === null) {
            return $result;
        }

        return array_merge($result, $social);
    }
}
