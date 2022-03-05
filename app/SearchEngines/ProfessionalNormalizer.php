<?php

namespace App\SearchEngines;

use App\Http\Resources\ProfessionalResource;
use App\Models\Professional as ProfessionalModel;

class ProfessionalNormalizer
{
    public static function toSearchableArray(ProfessionalModel $professionalModel): array
    {
        $professional = new ProfessionalResource($professionalModel);

        return $professional->toArray();
    }
}
