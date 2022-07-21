<?php


namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Modules\LoginVerification\LoginVerificationInterface;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    /** @var string */
    private const AUTH_TOKEN_NAME = 'auth-token';

    public function __construct(
        private UserRepository $userRepository,
        private LoginVerificationInterface $loginVerification
    ) {
    }

    public function login(string $phone, string $code): User
    {
        $user = $this->userRepository->getUserByPhone($phone);
        if (!$user) {
            throw new ModelNotFoundException('User doesnt exist');
        }

        if ($this->loginVerification->verify($phone, $code) === false) {
            throw new InvalidArgumentException('Invalid input provided');
        }

        Auth::login($user);

        return $user;
    }

    public function register(string $phone): User
    {
        return $this->userRepository->createUser(['phone' => $phone]);
    }

    public function createAuthToken(User $user): string
    {
        return $user->createToken(self::AUTH_TOKEN_NAME)->plainTextToken;
    }

    public function sendSms(User $user): bool
    {
        return $this->loginVerification->sendLoginMessage($user->phone);
    }

    public function logout(User $user): bool
    {
        return (bool) $user->tokens()->delete();
    }

    public function getLoggedUser(Request $request): User
    {
        return $this->userRepository->getUserByEmail($request->user()->email);
    }

    public function getUserByPhone(string $phone): ?User
    {
        return $this->userRepository->getUserByPhone($phone);
    }

    public function getUserFromToken(string $token): ?User
    {
        return PersonalAccessToken::findToken($token)?->tokenable;
    }
}
