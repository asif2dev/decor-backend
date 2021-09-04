<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function register(RegisterFormRequest $request): JsonResponse
    {
        $formData = $request->all();

        $accessToken = null;
        if (isset($formData['fbAccessToken'])) {
            $accessToken = $this->authService->loginFb($formData);
        } else {
            $user = $this->authService->register($formData);
            $accessToken = $this->authService->createAuthToken($user);
        }

        return response()
            ->json(['authToken' => $accessToken]);
    }
}
