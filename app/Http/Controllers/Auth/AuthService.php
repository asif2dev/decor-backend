<?php


namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Modules\SMS\SMSInterface;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class AuthService
{
    /** @var string */
    private const AUTH_TOKEN_NAME = 'auth-token';

    public function __construct(
        private UserRepository $userRepository,
        private SMSInterface $sms
    ) {
    }

    public function login(string $phone, string $code): User
    {
        $user = $this->userRepository->getUserByPhone($phone);
        if (!$user) {
            throw new ModelNotFoundException('User doesnt exist');
        }

        if ($user->verification_code !== $code) {
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

    public function sendSms(User $user, string $code): bool
    {
        $user->verification_code = $code;
        $user->save();

        return $this->sms->sendLoginMessage($user->phone, $code);
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
}
