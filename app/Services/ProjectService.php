<?php


namespace App\Services;


use App\Models\Professional;
use App\Models\Project;
use App\Models\User;
use App\Repositories\ProfessionalRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Collection;

class ProjectService
{
    public function __construct(private ProjectRepository $projectRepository, private ImageHandler $imageHandler)
    {
    }

    public function create(Professional $professional, array $data, array $images = []): Project
    {
        /** @var Project $project */
        $project = $professional->projects()->create($data);

        $paths = [];
        foreach ($images as $image) {
            $paths[] = $this->imageHandler->uploadProjectImage($image);
        }

        $this->projectRepository->addProjectImages($project, $paths);

        return $project;
    }

    public function getLatestProjects(): Collection
    {
        return $this->projectRepository->getLatestProjects(8);
    }
}
