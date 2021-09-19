<?php


namespace App\Services;


use App\Models\Professional;
use App\Models\Project;
use App\Models\User;
use App\Repositories\ProfessionalRepository;
use App\Repositories\ProjectRepository;
use App\Services\Images\ImageHandlerInterface;
use Illuminate\Support\Collection;

class ProjectService
{
    private ImageHandlerInterface $projectImage;

    public function __construct(private ProjectRepository $projectRepository, private ImageHandler $imageHandler)
    {
        $this->projectImage = $this->imageHandler->project();
    }

    public function create(Professional $professional, array $data, array $images = []): Project
    {
        /** @var Project $project */
        $project = $professional->projects()->create($data);

        $paths = [];
        foreach ($images as $image) {
            $paths[] = $this->projectImage->uploadImage($image);
        }

        $this->projectRepository->addProjectImages($project, $paths);

        return $project;
    }

    public function getLatestProjects(): Collection
    {
        return $this->projectRepository->getLatestProjects(8);
    }

    public function getById(int $id): ?Project
    {
        return $this->projectRepository->getById($id);
    }
}
