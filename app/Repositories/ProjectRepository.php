<?php


namespace App\Repositories;


use App\Models\Professional;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProjectRepository extends BaseRepository
{

    public function create(array $data): Project
    {
        $data = $this->convertToSnakeCase($data);

        return Project::create($data);
    }

    public function addProjectImages(Project $project, array $images): Project
    {
        $images = array_map(
            function ($image) {
                return ['path' => $image];
            },
            $images
        );

        $project->images()->createMany($images);

        return $project;
    }

    public function getLatestProjects(int $count): Collection
    {
        $query = (new Project())->newQuery();

        return $query->orderBy('id', 'desc')
            ->take($count)
            ->get();
    }

    public function getById(int $id): ?Project
    {
        return Project::find($id);
    }
}
