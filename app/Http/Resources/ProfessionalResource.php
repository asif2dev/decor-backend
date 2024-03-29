<?php

namespace App\Http\Resources;

use App\Modules\Images\ImagePathGenerator;
use App\Modules\Images\ProfessionalLogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ProfessionalResource extends JsonResource
{
    public function __construct($resource, private bool $loadProjects = false)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     */
    public function toArray($request = null): array
    {
        $result = [
            'uid' => $this->resource->uid,
            'slug' => $this->resource->slug,
            'companyName' => $this->resource->company_name,
            'about' => $this->resource->about,
            'services' => $this->resource->services,
            'logo' => [
                'src' => new ProfessionalLogo($this->resource->logo),
                'full' => ImagePathGenerator::generateFullPath($this->resource->logo, 250, 250),
            ],
            'categories' => new CategoryResourceCollection($this->resource->categories),
            'phone1' =>  $this->parsePhone($this->resource->phone1),
            'phone2' =>  $this->parsePhone($this->resource->phone2),
            'latLng' =>  $this->resource->lat_lng,
            'fullAddress' =>  $this->resource->full_address,
            'workScope' =>  $this->resource->work_scope,
            'projectsCount' =>  $this->resource->projects()->count(),
            'reviewsCount' => $this->resource->reviews()->count(),
            'rating' => (float) ($this->resource->reviews()->avg('rating') ?? 0),
            'social' => $this->parseSocial($this->resource->social)
        ];

        if ($this->loadProjects) {
            $result['projects'] = $this->getProjects();
        }

        return $result;
    }

    private function getProjects(): Collection
    {
        $projects = collect();
        foreach ($this->resource->projects as $project) {
            $projects->push(new ProjectResource($projects, false));
        }

        return $projects;
    }

    private function parsePhone(string|null $phone): string|null
    {
        if ($phone === 'null' || $phone === null) {
            return null;
        }

        return $phone;
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
