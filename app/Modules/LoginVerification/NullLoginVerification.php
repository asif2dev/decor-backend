<?php


namespace App\Modules\LoginVerification;


class NullLoginVerification implements LoginVerificationInterface
{

    public function sendLoginMessage(string $phone, string $code): bool
    {
        return true;
    }
}
