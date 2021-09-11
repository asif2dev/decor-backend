<?php

namespace App\Http\Controllers;

use App\Http\Forms\SearchForm;
use App\Http\Requests\CreateProfessionalRequest;
use App\Http\Resources\ProfessionalResource;
use App\Http\Resources\ProfessionalResourceCollection;
use App\Modules\SearchEngine\SearchEngineInterface;
use App\Services\ProfessionalService;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function __construct(
        private ProfessionalService $professionalService,
        private SearchEngineInterface $searchEngine
    ) {
    }

    public function store(CreateProfessionalRequest $request): ProfessionalResource
    {
        $user = $request->user();

        return new ProfessionalResource(
            $this->professionalService->create($user, $request->all())
        );
    }

    public function getTopRated(Request $request): ProfessionalResourceCollection
    {
        return new ProfessionalResourceCollection(
            $this->professionalService->getTopRated()
        );
    }

    public function search(Request $request): ProfessionalResourceCollection
    {
        $searchForm = new SearchForm($request->all());

        return new ProfessionalResourceCollection(
            $this->searchEngine->search($searchForm)
        );
    }
}
