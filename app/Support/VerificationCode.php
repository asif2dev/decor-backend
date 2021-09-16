<?php


namespace App\Support;


class VerificationCode
{
    public static function generate(): int
    {
        return rand(1000, 9999);
    }
}
