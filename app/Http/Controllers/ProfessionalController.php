<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthService;
use App\Http\Forms\SearchForm;
use App\Http\Requests\CreateProfessionalRequest;
use App\Http\Resources\MiniProfessionalResourceCollection;
use App\Http\Resources\ProfessionalProfileResource;
use App\Http\Resources\ProfessionalResource;
use App\Http\Resources\ProfessionalResourceCollection;
use App\Modules\SearchEngine\SearchEngineInterface;
use App\Services\ProfessionalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ProfessionalController extends Controller
{
    public function __construct(
        private ProfessionalService $professionalService,
        private AuthService $authService,
        private SearchEngineInterface $searchEngine
    ) {
    }

    public function get(string $professionalSlug): ProfessionalProfileResource
    {
        $professional = $this->professionalService->getBySlug($professionalSlug);
        if ($professional === null) {
            abort(404);
        }

        return new ProfessionalProfileResource($professional);
    }

    public function store(Request $request): JsonResponse|ProfessionalResource
    {
        $validator = Validator::make(
            $request->all(),
            [
                'companyName' => 'required',
                'about' => 'required',
                'categories' => 'required|array',
                'workScope' => 'required',
                'phone1' => 'required|min:11|unique:professionals,phone1',
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

        if ($request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            $user = $token->tokenable;
        } else {
            $user = $this->authService->register($request->get('phone1'));
        }

        $logo = $request->file('logo');

        return new ProfessionalResource(
            $this->professionalService->create($user, $request->all(), $logo)
        );
    }

    public function getTopRated(): ProfessionalResourceCollection
    {
        return new ProfessionalResourceCollection(
            $this->searchEngine->getTopRated()
        );
    }

    public function search(Request $request): MiniProfessionalResourceCollection
    {
        $searchForm = new SearchForm($request->all());

        return new MiniProfessionalResourceCollection(
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
