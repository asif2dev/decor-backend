<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
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
