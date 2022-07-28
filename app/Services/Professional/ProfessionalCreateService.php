<?php

namespace App\Services\Professional;

use App\Http\Controllers\Auth\AuthService;
use App\Models\Professional;
use App\Repositories\ProfessionalRepository;
use App\Services\ProfessionalService;
use App\Support\UserDetector;
use Illuminate\Http\Request;

class ProfessionalCreateService
{
    public function __construct(
        private ProfessionalRepository $professionalRepository,
        private ProfessionalService $professionalService,
        private AuthService $authService
    ) {
    }

    public function create(Request $request): ?Professional
    {
        $professional = null;
        $this->professionalRepository->transaction(
            function () use ($request, &$professional) {
                $data = $request->all();

                $user = UserDetector::fromRequest($request);
                if (!$user) {
                    $user = $this->authService->register($request->get('phone1'));
                }

                $data['uid'] = (int)(time() . rand(100, 999));

                $professional = $this->professionalRepository->create($data);
                $this->professionalService->updateSlug($professional);
                $this->professionalService->syncCategories($professional, $data['categories']);
                $this->professionalService->attachUser($professional,$user->id);
                $this->professionalService->updateLogo($professional, $request->file('logo'));
            }
        );

        return $professional;
    }
}
