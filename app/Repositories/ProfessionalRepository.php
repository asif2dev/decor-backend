<?php


namespace App\Repositories;


use App\Http\Forms\SearchForm;
use App\Models\Professional;
use Illuminate\Database\Eloquent\Builder;
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
        $result = \DB::select(
            'SELECT
                            professionals.id, pp.projectsCount, pr.avgReviews
                        FROM
                            professionals
                        LEFT JOIN(
                            SELECT
                                COUNT(projects.id) as projectsCount,
                                professionals.id
                            FROM
                                professionals
                            LEFT JOIN projects ON professionals.id = projects.professional_id
                            GROUP BY
                                professionals.id
                        ) pp ON pp.id = professionals.id
                        left join (
                            SELECT
                                AVG(professional_reviews.rating) as avgReviews,
                                professionals.id
                            FROM
                                professionals
                            left JOIN professional_reviews ON professionals.id = professional_reviews.professional_id
                            GROUP BY
                                professionals.id
                        ) as pr on pr.id = professionals.id
                        order by projectsCount desc, avgReviews desc
                        limit 0, 5'
        );

        $ids = collect($result)->pluck('id')->flatten()->toArray();
        $idsAsString = implode(',', $ids);

        return $query->whereIn('id', $ids)
            ->orderByRaw("FIND_IN_SET('id','$idsAsString')")
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
        $query->when(
            $searchForm->getCategory(),
            fn($q) => $q->whereRelation('categories', 'slug', $searchForm->getCategory())
        );

        $query->when(
            $searchForm->getCity(),
            fn(Builder $q) => $q->where('work_scope', 'like', '%' . $searchForm->getCity() . '%')
        );

        return $query->get();
    }

    public function update(Professional $professional, array $data): Professional
    {
        $data = $this->convertToSnakeCase($data);

        $professional->update($data);

        return $professional;
    }
}
