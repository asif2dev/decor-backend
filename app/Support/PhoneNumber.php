<?php


namespace App\Support;


class PhoneNumber
{
    public static function isDigits(string $phone): bool
    {
        return preg_match('/^[0-9]{10,14}\z/', $phone);
    }

    public static function isValid(string $phone): bool
    {
        if (preg_match('/^[+][0-9]/', $phone)) {
            $count = 1;
            $phone = str_replace(['+'], '', $phone, $count);
        }

        //remove white space, dots, hyphens and brackets
        $phone = str_replace([' ', '.', '-', '(', ')'], '', $phone);

        return self::isDigits($phone);
    }
}
