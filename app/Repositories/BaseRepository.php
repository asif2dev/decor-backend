<?php


namespace App\Repositories;


use Illuminate\Support\Str;

class BaseRepository
{
    public function convertToSnakeCase(array $inputs): array
    {
        foreach ($inputs as $key => $value) {
            unset($inputs[$key]);
            $inputs[Str::snake($key)] = $value;
        }

        return $inputs;
    }
}
