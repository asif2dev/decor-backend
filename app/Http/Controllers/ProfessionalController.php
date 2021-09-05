<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProfessionalRequest;
use App\Http\Resources\ProfessionalResource;
use App\Http\Resources\ProfessionalResourceCollection;
use App\Services\ProfessionalService;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function __construct(private ProfessionalService $professionalService)
    {
    }

    public function store(CreateProfessionalRequest $request): ProfessionalResource
    {
        return new ProfessionalResource($this->professionalService->create($request->all()));
    }

    public function getTopRated(Request $request): ProfessionalResourceCollection
    {
        return new ProfessionalResourceCollection(
            $this->professionalService->getTopRated()
        );
    }
}
