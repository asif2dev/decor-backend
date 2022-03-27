<?php

namespace App\Modules\SearchEngine;

use App\Http\Forms\SearchForm;
use App\Models\Professional;
use Illuminate\Support\Collection;
use MeiliSearch\Http\Client;
use MeiliSearch\Endpoints\Indexes;

class MeilisearchSearchEngine implements SearchEngineInterface
{
    public function configure(): void
    {
//        $http = new Client(
//            config('scout.meilisearch.host'),
//            config('scout.meilisearch.key'),
//        );
//
//        $index = new Indexes($http, (new Professional)->searchableAs());
//        $index->updateSortableAttributes(['reviewsCount', 'projectsCount']);
    }

    public function search(SearchForm $searchForm): Collection
    {
        return Professional::search('', function (Indexes $meilisearch, $query, $options) use ($searchForm){
            $filter = 'categories.name="' . $searchForm->getCategory() . '" and workScope = "' . $searchForm->getCity() . '"';

            logger()->info($filter);

            $options['filter'] = $filter;
            $options['sort'] = [
                'reviewsCount:desc',
                'projectsCount:desc',
            ];

            return $meilisearch->search($query, $options);

        })->get();
    }

    public function getTopRated(): Collection
    {
        return Professional::search()
            ->orderBy('reviewsCount', 'desc')
            ->orderBy('projectsCount', 'desc')
            ->get();
    }
}
