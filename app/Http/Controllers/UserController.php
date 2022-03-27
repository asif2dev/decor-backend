<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfessionalResourceCollection;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Services\ProfessionalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private ProfessionalService $professionalService
    ) {
    }

    public function toggleFavorites(Request $request, string $professionalUid): JsonResponse
    {
        $professional = $this->professionalService->getByUid($professionalUid);
        if (!$professional) {
            return new JsonResponse();
        }

        $this->userRepository->toggleFavorites($request->user(), $professional);

        return new JsonResponse();
    }

    public function getFavorites(Request $request): ProfessionalResourceCollection
    {
        $professionals = $this->userRepository->getFavorites($request->user());

        return new ProfessionalResourceCollection($professionals);
    }

    public function getLoggedInUser(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function updateUser(Request $request): UserResource
    {
        $this->userRepository->updateUser($request->user(), $request->all());

        return new UserResource($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([], Response::HTTP_OK);
    }
}
