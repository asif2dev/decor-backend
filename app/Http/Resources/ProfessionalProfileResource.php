<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Modules\Images\ProfessionalLogo;
use App\Modules\Images\ProjectImage as ProjectImagePath;
use App\Modules\Images\ProjectThumb;
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
    public function toArray($request = null): array
    {
        return [
            'uid' => $this->resource->uid,
            'slug' => $this->resource->slug,
            'viewsCount' => $this->resource->views_count,
            'companyName' => $this->resource->company_name,
            'logo' => new ProfessionalLogo($this->resource->logo),
            'about' => $this->resource->about,
            'services' => $this->resource->services,
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
            $projects->push($this->getProject($project));
        }

        return $projects;
    }

    private function getProject(Project $project): array
    {
        $image = $project->images->first();

        return [
            'id' => $project->id,
            'slug' => $project->slug,
            'title' => $project->title,
            'description' => $project->description,
            'images' => [
                [
                    'src' => new ProjectImagePath($image->path),
                    'thumbnail' => new ProjectThumb($image->path)
                ]
            ]
        ];
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
