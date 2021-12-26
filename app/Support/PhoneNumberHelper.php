<?php


namespace App\Support;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberHelper
{
    public static function getFormattedPhone(string $phone): string
    {
        $util =  PhoneNumberUtil::getInstance();

        return $util->format(self::getPhoneObject($phone), PhoneNumberFormat::E164);
    }

    public static function isValid(string $phone): bool
    {
        $util =  PhoneNumberUtil::getInstance();

        return $util->isValidNumber(self::getPhoneObject($phone));
    }

    public static function getPhoneObject(string $phone): PhoneNumber
    {
        $util =  PhoneNumberUtil::getInstance();

        return $util->parse($phone, 'en');
    }
}
