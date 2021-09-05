<?php


namespace App\Repositories;


use App\Models\Professional;
use App\Models\ProfessionalReview;

class ProfessionalReviewsRepository extends BaseRepository
{
    public function writeReview(Professional $professional, array $data): ProfessionalReview
    {
        return $professional->reviews()->create($data);
    }
}
