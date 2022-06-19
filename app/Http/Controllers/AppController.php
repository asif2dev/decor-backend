<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
use App\Http\Resources\DesignTypeResource;
use App\Http\Resources\DesignTypeResourceCollection;
use App\Http\Resources\SpaceResourceCollection;
use App\Http\Resources\TagsResourceCollection;
use App\Models\Category;
use App\Models\DesignType;
use App\Models\Space;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function getAppConfig(): JsonResponse
    {
        $categories = new CategoryResourceCollection(Category::get());
        $spaces = new SpaceResourceCollection(Space::get());
        $tags = new TagsResourceCollection(Tag::get());
        $designTypes = new DesignTypeResourceCollection(DesignType::get());

        return new JsonResponse(
            [
                'categories' => $categories,
                'spaces' => $spaces,
                'tags' => $tags,
                'designTypes' => $designTypes
            ]
        );
    }
}
