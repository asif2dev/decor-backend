<?php


namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /** @var string */
    private const AUTH_TOKEN_NAME = 'auth-token';

    public function __construct(private UserRepository $userRepository, private Hasher $hasher)
    {
    }

    public function register(array $userData): User
    {
        return $this->userRepository->createUser($userData);
    }

    public function createAuthToken(User $user): string
    {
        return $user->createToken(self::AUTH_TOKEN_NAME)->plainTextToken;
    }

    public function login(array $credentials): ?User
    {
        $user = $this->userRepository->getUserByEmail($credentials['email']);
        if ($user === null) {
            return null;
        }

        if ($this->hasher->check($credentials['password'], $user->getAuthPassword()) === false) {
            return null;
        }

        Auth::login($user);

        return $user;
    }

    public function logout(User $user): bool
    {
        return (bool) $user->tokens()->delete();
    }

    public function getLoggedUser(Request $request): User
    {
        return $this->userRepository->getUserByEmail($request->user()->email);
    }

    public function loginFb(array $userData): string
    {
        $user = $this->userRepository->getUserByEmail($userData['email']);
        if (!$user) {
            $user = $this->userRepository->createUser($userData);
        }

        Auth::login($user);

        return $this->createAuthToken($user);
    }
}
