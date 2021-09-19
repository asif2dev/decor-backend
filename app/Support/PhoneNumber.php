<?php


namespace App\Support;


use JetBrains\PhpStorm\ArrayShape;

class PhoneNumber
{
    public static function isDigits(string $phone): bool
    {
        return preg_match('/^[0-9]{10,14}\z/', $phone);
    }

    #[ArrayShape(['country' => "string", 'phone' => "string"])]
    public static function extractPhone(string $phone): array
    {
        [$country, $phone] = explode(' ', $phone);

        return [
            'country' => $country,
            'phone' => $phone
        ];
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
