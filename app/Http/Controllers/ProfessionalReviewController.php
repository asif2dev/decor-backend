<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfessionalReviewResource;
use App\Http\Resources\ProfessionalReviewResourceCollection;
use App\Services\ProfessionalReviewsService;
use App\Services\ProfessionalService;
use Illuminate\Http\Request;

class ProfessionalReviewController extends Controller
{
    public function __construct(
        private ProfessionalService $professionalService,
        private ProfessionalReviewsService $professionalReviewsService
    ) {
    }

    public function getReviews(int $uid): ProfessionalReviewResourceCollection
    {
        $professional = $this->professionalService->getByUid($uid);
        if (!$professional) {
            abort(403);
        }

        return new ProfessionalReviewResourceCollection(
            $professional->reviews
        );
    }

    public function writeReview(Request $request, int $uid): ProfessionalReviewResource
    {
        $professional = $this->professionalService->getByUid($uid);
        if (!$professional) {
            abort(403);
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        return new ProfessionalReviewResource(
            $this->professionalReviewsService->writeReview($professional, $data)
        );
    }
}
