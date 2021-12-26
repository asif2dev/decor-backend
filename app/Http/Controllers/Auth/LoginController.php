<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PhoneNumberHelper;
use App\Support\VerificationCode;
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
        if ($request->has('phone') === false) {
            abort(400);
        }

        $phone = $request->get('phone');
        if (PhoneNumberHelper::isValid($phone) === false) {
            abort(422);
        }

        $phone = PhoneNumberHelper::getFormattedPhone($phone);

        $user = $this->authService->getUserByPhone($phone);
        if ($user === null) {
            $user = $this->authService->register($phone);
        }

        $code = VerificationCode::generate();

        $this->authService->sendSms($user, $code);

        return response()->json([]);
    }

    public function verify(Request $request): JsonResponse
    {
        if ($request->has('code') === false || $request->has('phone') === false) {
            abort(400);
        }

        $phone = $request->get('phone');
        $code = $request->get('code');

        $phone = PhoneNumberHelper::getFormattedPhone($phone);

        $user = $this->authService->login($phone, $code);

        $authToken = $this->authService->createAuthToken($user);

        return response()->json(['authToken' => $authToken]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
