<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request): JsonResponse
    {
        $user = $this->authService->login($request->only(['email', 'password']));
        if ($user === null) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        $authToken = $this->authService->createAuthToken($user);

        return response()->json(['authToken' => $authToken]);
    }

    public function loginFB(Request $request): JsonResponse
    {
        $authToken = $this->authService->loginFb($request->all());

        return response()->json(['authToken' => $authToken]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
