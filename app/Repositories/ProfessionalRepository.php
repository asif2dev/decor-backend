<?php


namespace App\Repositories;


use App\Models\Professional;
use Illuminate\Support\Collection;

class ProfessionalRepository extends BaseRepository
{

    public function create(array $data): Professional
    {
        $data = $this->convertToSnakeCase($data);

        return (new Professional)->newQuery()->create($data);
    }

    public function getTopRated(): Collection
    {
        $query = (new Professional())->newQuery();

        return $query->orderBy('id', 'desc')
            ->skip(0)
            ->take(5)
            ->get();
    }
}
