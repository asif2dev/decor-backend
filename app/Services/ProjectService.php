<?php


namespace App\Services;


use App\Models\Professional;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Repositories\ProjectRepository;
use App\Repositories\TagsRepository;
use App\Services\Images\ImageHandlerInterface;
use App\Support\Str;
use Illuminate\Support\Collection;

class ProjectService
{
    private ImageHandlerInterface $projectImage;

    public function __construct(
        private ProjectRepository $projectRepository,
        private ImageHandler $imageHandler,
        private TagsRepository $tagsRepository
    ) {
        $this->projectImage = $this->imageHandler->project();
    }

    public function create(Professional $professional, array $data, array $images = []): Project
    {
        $tags = $this->tagsRepository->syncTags($data['tags']);

        /** @var Project $project */
        $project = $professional->projects()->create($data);

        $project->tags()->sync($tags->pluck('id')->flatten()->toArray());

        $paths = [];
        foreach ($images as $image) {
            $paths[] = $this->projectImage->uploadImage($image);
        }

        $this->projectRepository->addProjectImages($project, $professional, $paths);

        return $project;
    }

    public function syncTags(Project $project, array $tagsIds): void
    {
        $project->tags()->sync($tagsIds);
    }

    public function updateSlug(Project $project): void
    {
        $project->slug = Str::arSlug($project->title) . '-' . $project->id . rand(999, 9999);
        $project->save();
    }

    public function uploadImages(Project $project, array $images): void
    {
        $paths = [];
        foreach ($images as $image) {
            $paths[] = $this->projectImage->uploadImage($image);
        }

        $this->projectRepository->addProjectImages($project, $project->professional, $paths);
    }

    public function getLatestProjects(): Collection
    {
        return $this->projectRepository->getLatestProjects(8);
    }

    public function getById(int $id): ?Project
    {
        return $this->projectRepository->getById($id);
    }

    public function update(
        Project $project,
        array $data,
        array $images
    ): void {
        $deletedImages = $project->images()
            ->whereNotIn('id', $data['currentImages'] ?? [])
            ->get();

        foreach ($deletedImages as $image) {
            /** @var ProjectImage $image*/
            $this->projectImage->removeImage($image->path);
        }
        $project->images()->whereNotIn('id', $data['currentImages'])->delete();

        $tags = $this->tagsRepository->syncTags($data['tags']);

        $project->tags()->sync($tags->pluck('id')->flatten()->toArray());

        if (!empty($images)) {
            $paths = [];
            foreach ($images as $image) {
                $paths[] = $this->projectImage->uploadImage($image);
            }
            $this->projectRepository->addProjectImages($project, $project->professional, $paths);
        }

        $this->projectRepository->update($project, $data);
    }

    public function delete(Project $project): void
    {
        $project->images()->delete();

        $this->projectRepository->delete($project);
    }

    public function inspire(?string $tag): Collection
    {
        return $this->projectRepository->getProjectsHasTag($tag);
    }
}
