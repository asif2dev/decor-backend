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
        private ProjectImageService $projectImageService,
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

    public function getBySlug(string $slug): ?Project
    {
        return $this->projectRepository->getBySlug($slug);
    }

    public function update(Project $project, array $data, array $images): void
    {
        $this->projectRepository->transaction(function () use ($project, $data, $images) {
            $deletedImages = $project->images()
                ->whereNotIn('id', $data['currentImages'] ?? [])
                ->get();

            $this->projectImageService->deleteImages($deletedImages);
            $tags = $this->tagsRepository->syncTags($data['tags']);
            $this->syncTags($project, $tags->pluck('id')->flatten()->toArray());
            $this->updateSlug($project);

            if (!empty($images)) {
                $this->uploadImages($project, $images);
            }

            $this->projectRepository->update($project, $data);
        });

        $project->images()->searchable();
        $project->searchable();
    }

    public function delete(Project $project): void
    {
       $this->projectImageService->deleteImages($project->images);

       $project->images()->unsearchable();
       $project->unsearchable();
        $this->projectRepository->delete($project);
    }

    public function inspire(?string $tag): Collection
    {
        return $this->projectRepository->getProjectsHasTag($tag);
    }
}
