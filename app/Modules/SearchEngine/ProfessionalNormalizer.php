<?php

namespace App\Modules\SearchEngine;

use App\Http\Resources\ProfessionalProfileResource;
use App\Models\Professional as ProfessionalModel;

class ProfessionalNormalizer
{
    public static function toSearchableArray(ProfessionalModel $professionalModel): array
    {
        $professional = new ProfessionalProfileResource($professionalModel);

        return $professional->toArray();
    }
}
