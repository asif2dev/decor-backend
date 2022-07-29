<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Repositories\ProjectImageRepository;
use App\Support\Str;

class ProjectImageService
{
    public function __construct(private ProjectImageRepository $projectImageRepository)
    {
    }

    public function getImageBySlug(string $slug): ?ProjectImage
    {
        return $this->projectImageRepository->getBySlug($slug);
    }

    public function updateImages(Project $project, array $imagesData): bool
    {
        foreach ($imagesData as $image) {
            $project->images()->where('id', $image['id'])
                ->update([
                    'title' => $image['title'],
                    'slug' => Str::arSlug($image['title']) . '-' . $image['id'],
                    'space_id' => empty($image['space_id']) ? null : $image['space_id'],
                    'design_type_id' => empty($image['design_type_id']) ? null : $image['design_type_id'],
                    'description' => $image['description'],
                    'palette' => $image['palette'],
                ]);
        }

        return true;
    }
}
