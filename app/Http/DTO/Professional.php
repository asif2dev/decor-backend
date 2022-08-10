<?php

namespace App\Http\DTO;

use App\Models\Professional as ProfessionalModel;

class Professional
{
    public string $uid;
    public string $slug;
    public string $company_name;
    public string $about;
    public ImageSet $logo;
    public string $projectsCount;
    public string $reviewsCount;
    public string $rating;

    public function projectsCount(ProfessionalModel $professional): int
    {
        return $professional->projects->count();
    }

    public function rating(ProfessionalModel $professional): float
    {
        return  (float) ($professional->reviews()->avg('rating') ?? 0);
    }

    public function reviewsCount(ProfessionalModel $professional): int
    {
        return $professional->reviews->count();
    }

    public function logo(): ImageSet
    {
        return new ImageSet();
    }
}
