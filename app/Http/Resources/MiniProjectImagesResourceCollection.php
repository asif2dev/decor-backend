<?php

namespace App\Http\Resources;

use App\Models\ProjectImage;
use App\Modules\Images\ProfessionalLogo;
use App\Modules\Images\ProjectThumb;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MiniProjectImagesResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->resource->map(function (ProjectImage $projectImage) {
            return [
                'slug' => $projectImage->slug,
                'thumbnail' => new ProjectThumb($projectImage->path),
                'space_id' => $projectImage->space_id,
                'professional' => [
                    'uid' => $projectImage->professional->uid,
                    'companyName' => $projectImage->professional->company_name,
                    'logo' => new ProfessionalLogo($projectImage->professional->logo),
                ]
            ];
        });
    }
}
