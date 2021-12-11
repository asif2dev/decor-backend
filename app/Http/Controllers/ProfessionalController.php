<?php

namespace App\Http\Controllers;

use App\Http\Forms\SearchForm;
use App\Http\Requests\CreateProfessionalRequest;
use App\Http\Resources\ProfessionalProfileResource;
use App\Http\Resources\ProfessionalResource;
use App\Http\Resources\ProfessionalResourceCollection;
use App\Modules\SearchEngine\SearchEngineInterface;
use App\Services\ProfessionalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfessionalController extends Controller
{
    public function __construct(
        private ProfessionalService $professionalService,
        private SearchEngineInterface $searchEngine
    ) {
    }

    public function get(string $professionalUid): ProfessionalProfileResource
    {
        $professional = $this->professionalService->getByUid($professionalUid);
        if ($professional === null) {
            abort(404);
        }

        return new ProfessionalProfileResource($professional);
    }

    public function store(Request $request): JsonResponse|ProfessionalResource
    {
        logger()->info('Professional requests', [
            'payload' => $request->all()
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'companyName' => 'required',
                'about' => 'required',
                'categoryId' => 'required',
                'phone1' => 'required|min:11',
                'latLng' => 'required',
                'fullAddress' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->getMessageBag()->all()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }


        $user = $request->user();
        $logo = $request->file('logo');

        return new ProfessionalResource(
            $this->professionalService->create($user, $request->all(), $logo)
        );
    }

    public function getTopRated(): ProfessionalResourceCollection
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

    public function update(Request $request, int $professionalUid): ProfessionalResource
    {
        $professional = $this->professionalService->getByUid($professionalUid);
        if ($professional === null) {
            abort(404);
        }

        if ($this->professionalService->hasUser($professional, $request->user()) === false) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $professional = $this->professionalService->update($professional, $request->all(), $request->file('logo'));

        return new ProfessionalResource($professional);
    }
}
