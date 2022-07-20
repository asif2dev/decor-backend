<?php

namespace App\Services;

use App\Models\ProjectImage;
use App\Repositories\ProjectImageRepository;

class ProjectImageService
{
    public function __construct(private ProjectImageRepository $projectImageRepository)
    {
    }

    public function getImageBySlug(string $slug): ?ProjectImage
    {
        return $this->projectImageRepository->getBySlug($slug);
    }
}
