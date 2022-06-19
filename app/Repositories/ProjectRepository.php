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

    public function addProjectImages(Project $project, Professional $professional, array $images): Project
    {
        $images = array_map(
            function ($image) use ($professional) {
                return ['path' => $image, 'professional_id' => $professional->id];
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

    public function update(Project $project, array $data): void
    {
        $data = $this->convertToSnakeCase($data);

        Project::where('id', $project->id)->update(
            [
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        );
    }

    public function delete(Project $project): void
    {
        Project::where('id', $project->id)->delete();
    }

    public function getProjectsHasTag(?string $tag): Collection
    {
        return (new Project())->newQuery()
            ->whereHas(
                'tags',
                fn($query) => $query->where('name', 'like', '%' . $tag . '%')
            )
            ->get();
    }
}
