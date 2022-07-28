<?php


namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaseRepository
{
    public function transaction(callable $callable, int $attempts = 1): void
    {
        DB::transaction($callable, $attempts);
    }

    protected function convertToSnakeCase(array $inputs): array
    {
        foreach ($inputs as $key => $value) {
            unset($inputs[$key]);
            $inputs[Str::snake($key)] = $value;
        }

        return $inputs;
    }
}
