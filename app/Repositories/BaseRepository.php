<?php


namespace App\Repositories;


use Illuminate\Support\Str;

class BaseRepository
{
    protected function convertToSnakeCase(array $inputs): array
    {
        foreach ($inputs as $key => $value) {
            unset($inputs[$key]);
            $inputs[Str::snake($key)] = $value;
        }

        return $inputs;
    }
}
