<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpaceResourceCollection;
use App\Models\Space;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    public function get(): SpaceResourceCollection
    {
        return new SpaceResourceCollection(Space::query()->get());
    }
}
