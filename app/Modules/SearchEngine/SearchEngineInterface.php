<?php


namespace App\Modules\SearchEngine;


use App\Http\Forms\SearchForm;
use Illuminate\Support\Collection;

interface SearchEngineInterface
{
    public function search(SearchForm $searchForm): Collection;
}