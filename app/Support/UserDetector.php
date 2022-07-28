<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserDetector
{
    public static function fromRequest(Request $request): ?User
    {
        if ($request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            return $token->tokenable;
        }

        return null;
    }
}
