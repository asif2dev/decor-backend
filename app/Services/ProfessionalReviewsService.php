<?php


namespace App\Services;


use App\Models\Professional;
use App\Models\ProfessionalReview;
use App\Repositories\ProfessionalReviewsRepository;

class ProfessionalReviewsService
{
    public function __construct(private ProfessionalReviewsRepository $professionalReviewsRepository)
    {
    }

    public function writeReview(Professional $professional, array $data): ProfessionalReview
    {
        return $this->professionalReviewsRepository->writeReview($professional, $data);
    }
}
