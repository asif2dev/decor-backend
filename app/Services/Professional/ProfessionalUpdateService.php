<?php

namespace App\Services\Professional;

use App\Models\Professional;
use App\Repositories\ProfessionalRepository;
use App\Services\ImageHandler;
use App\Services\Images\ImageHandlerInterface;
use App\Services\ProfessionalService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ProfessionalUpdateService
{
    private ImageHandlerInterface $professionalImage;

    public function __construct(
        private ProfessionalRepository $professionalRepository,
        private ProfessionalService $professionalService,
        ImageHandler $imageHandler
    ) {
        $this->professionalImage = $imageHandler->professional();
    }

    public function update(Request $request, Professional $professional): Professional
    {
        $this->professionalRepository->transaction(
            function () use ($request, &$professional) {
                $data = $request->all();

                $this->professionalRepository->update($professional, $data);

                if (isset($data['categories'])) {
                    $this->professionalService->syncCategories($professional, $data['categories']);
                }

                if ($request->hasFile('logo')) {
                    $this->updateLogo($professional, $request->file('logo'));
                }

                $this->professionalService->updateSlug($professional);

                $professional->refresh();
            }
        );

        $professional->searchable();

        return $professional;
    }

    private function updateLogo(Professional $professional, UploadedFile $logo): void
    {
        $oldLogoPath = $professional->logo;
        $this->professionalService->updateLogo($professional, $logo);
        $this->professionalImage->removeImage($oldLogoPath);
    }
}
