<?php


namespace App\Modules\LoginVerification;


interface LoginVerificationInterface
{
    public function sendLoginMessage(string $phone, string $code): bool;
}
