<?php

namespace App\Http\Resources;

use App\Models\Professional;
use App\Modules\Images\ImagePathGenerator;
use App\Modules\Images\ProfessionalLogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MiniProfessionalResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request = null)
    {
        return $this->resource->map(fn(Professional $professional) => [
            'uid' => $professional->uid,
            'slug' => $professional->slug,
            'companyName' => $professional->company_name,
            'about' => $professional->about,
            'logo' => [
                'src' => new ProfessionalLogo($professional->logo),
                'full' => ImagePathGenerator::generateFullPath($professional->logo),
            ],
            'projectsCount' => $professional->projects()->count(),
            'reviewsCount' => $professional->reviews()->count(),
            'rating' => (float)($professional->reviews()->avg('rating') ?? 0)
        ]);
    }
}
