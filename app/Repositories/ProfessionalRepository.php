<?php


namespace App\Repositories;


use App\Http\Forms\SearchForm;
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

    public function getByUid(int $uid): ?Professional
    {
        return Professional::whereUid($uid)->first();
    }

    public function getAll(): Collection
    {
        return Professional::get();
    }

    public function search(SearchForm $searchForm): Collection
    {
        $query = (new Professional())->newQuery();
        $query->when($searchForm->getCategory(), fn($q) => $q->where('category_id', $searchForm->getCategory()));

        return $query->get();
    }

    public function update(Professional $professional, array $data): Professional
    {
        $data = $this->convertToSnakeCase($data);

        $professional->update($data);

        return $professional;
    }
}
