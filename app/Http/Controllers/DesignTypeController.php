<?php

namespace App\Http\Controllers;

use App\Models\DesignType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesignTypeController extends Controller
{
    public function getDesignTypes(): JsonResponse
    {
        $types = DesignType::query()->select(['id', 'slug', 'name'])->get();

        return new JsonResponse($types->toArray());
    }
}
