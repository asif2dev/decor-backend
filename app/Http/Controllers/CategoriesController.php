<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoriesController extends Controller
{
    public function getAll(): Collection
    {
        return Category::all();
    }
}
