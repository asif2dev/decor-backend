<?php


namespace App\Modules\LoginVerification;


use App\Models\User;

class LocalLoginVerification implements LoginVerificationInterface
{
    public function __construct()
    {
    }

    public function sendLoginMessage(string $phone, ?string $code = null): bool
    {
        return (new User())->newQuery()->where('phone', $phone)->update(['verification_code' => $code ?? rand(1111, 9999)]);
    }

    public function verify(string $phone, string $code): bool
    {
        return (new User())->newQuery()->where('phone', $phone)
            ->where('verification_code', $code)
            ->exists();
    }
}
