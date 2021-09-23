<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectsResourceCollection;
use App\Http\Resources\ProjectTagsResourceCollection;
use App\Models\Project;
use App\Models\ProjectTag;
use App\Services\ProfessionalService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projectService,
        private ProfessionalService $professionalService
    ) {
    }

    public function get(int $uid): ProjectResource
    {
        $project = $this->projectService->getById($uid);
        if (!$project) {
            abort(404);
        }

        return new ProjectResource($project);
    }

    public function getSimilar(string $uid): ProjectsResourceCollection
    {
        return new ProjectsResourceCollection(Project::get());
    }

    public function store(Request $request, int $uid): ProjectResource
    {
        $professional = $this->professionalService->getByUid($uid);
        if (!$professional) {
            abort(403);
        }

        $images = $request->hasFile('images') ? $request->file('images') : [];

        $project = $this->projectService->create($professional, $request->all(), $images);

        return new ProjectResource($project);
    }

    public function getLatestProjects(): ProjectsResourceCollection
    {
        $projects = $this->projectService->getLatestProjects();

        return new ProjectsResourceCollection($projects);
    }

    public function getProjects(int $uid): ProjectsResourceCollection
    {
        $professional = $this->professionalService->getByUid($uid);

        return new ProjectsResourceCollection($professional->projects);
    }

    public function getTags(): ProjectTagsResourceCollection
    {
        return new ProjectTagsResourceCollection(ProjectTag::get());
    }
}
