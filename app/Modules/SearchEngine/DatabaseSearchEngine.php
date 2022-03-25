<?php


namespace App\Modules\SearchEngine;


use App\Http\Forms\SearchForm;
use App\Services\ProfessionalService;
use Illuminate\Support\Collection;

class DatabaseSearchEngine implements SearchEngineInterface
{
    public function __construct(private ProfessionalService $professionalService)
    {
    }

    public function search(SearchForm $searchForm): Collection
    {
        return $this->professionalService->search($searchForm);
    }

    public function getTopRated(): Collection
    {
        return $this->professionalService->getTopRated();
    }
}
