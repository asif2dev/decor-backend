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
        return (new User())
            ->newQuery()
            ->where('phone', $phone)
            ->update(['verification_code' => $code ?? rand(111111, 999999)]);
    }

    public function verify(string $phone, string $code): bool
    {
        logger()->info('logging using: ', ['phone' => $phone, 'code' => $code]);
        return (new User())->newQuery()->where('phone', $phone)
            ->where('verification_code', $code)
            ->exists();
    }
}
