<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Repositories\ProjectImageRepository;
use App\Services\Images\ImageHandlerInterface;
use App\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProjectImageService
{
    private ImageHandlerInterface $projectImage;

    public function __construct(
        private ProjectImageRepository $projectImageRepository,
        private ImageHandler $imageHandler
    ) {
        $this->projectImage = $this->imageHandler->project();
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

        $project->images()->searchable();

        return true;
    }

    public function deleteImages(Collection $deletedImages): void
    {
        /** @var ProjectImage $image */

        foreach ($deletedImages as $image) {
            DB::table('user_favorite_project_images')
                ->where('project_image_id', $image->id)
                ->delete();
            $this->projectImage->removeImage($image->path);
            $image->delete();
        }
    }
}
