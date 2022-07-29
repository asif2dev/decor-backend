<?php

namespace App\Services\Project;

use App\Models\Professional;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\TagsRepository;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectCreateService
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectService $projectService,
        private TagsRepository $tagsRepository
    ) {
    }

    public function create(Request $request, Professional $professional): ?Project
    {
        $project = null;
        $this->projectRepository->transaction(
            function () use ($request, $professional, &$project) {
                $data = $request->all();
                $data['professional_id'] = $professional->id;

                $project = $this->projectRepository->create($data);
                $this->projectService->updateSlug($project);

                $tags = $this->tagsRepository->syncTags($data['tags']);
                $this->projectService->syncTags($project, $tags->pluck('id')->flatten()->toArray());

                $images = $request->hasFile('images') ? $request->file('images') : [];
                if (!empty($images)) {
                    $this->projectService->uploadImages($project, $images);
                }
            }
        );

        return $project;
    }
}
