<?php

namespace App\Support;

use \Illuminate\Support\Str as BaseStr;

class Str extends BaseStr
{
    public static function arSlug($string, $separator = '-'): string
    {
        $string = trim($string);

        $string = preg_replace("/[\s&\/\\\_]+/", $separator, $string);

        return rawurldecode($string);
    }
}
