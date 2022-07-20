<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfessionalResourceCollection;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Services\ProfessionalService;
use App\Services\ProjectImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private ProfessionalService $professionalService,
        private ProjectImageService $projectImageService
    ) {
    }

    public function toggleFavoriteProfessional(Request $request, string $professionalUid): JsonResponse
    {
        $professional = $this->professionalService->getByUid($professionalUid);
        if (!$professional) {
            return new JsonResponse();
        }

        $this->userRepository->toggleFavoriteProfessional($request->user(), $professional);

        return new JsonResponse();
    }

    public function toggleFavoriteProjectImage(Request $request, string $slug): JsonResponse
    {
        $projectImage = $this->projectImageService->getImageBySlug($slug);
        if (!$projectImage) {
            return new JsonResponse();
        }

        $this->userRepository->toggleFavoriteProjectImage($request->user(), $projectImage);

        return new JsonResponse();
    }

    public function getFavorites(Request $request): ProfessionalResourceCollection
    {
        $professionals = $this->userRepository->getFavorites($request->user());

        return new ProfessionalResourceCollection($professionals);
    }

    public function getLoggedInUser(Request $request): UserResource
    {
        $user = $request->user();
        if (! $user) {
            abort(404);
        }

        return new UserResource($request->user());
    }

    public function updateUser(Request $request): JsonResponse|UserResource
    {
        try {
            if ($this->userRepository->getUserByEmail($request->get('email')) !== null) {
                return \response()->json(['message' => 'البريد اللإلكتروني مستخدم من قبل'], 400);
            }

            $this->userRepository->updateUser($request->user(), $request->all());

            return new UserResource($request->user());
        } catch (\Throwable $exception) {
            logger()->info(get_class($exception));
            return \response()->json(['message' => 'لم يتم تحديث البيانات'], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([], Response::HTTP_OK);
    }
}
