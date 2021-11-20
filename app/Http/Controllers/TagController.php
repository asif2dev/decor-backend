<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagsResourceCollection;
use App\Models\Tag;

class TagController extends Controller
{
    public function getTags(): TagsResourceCollection
    {
        return new TagsResourceCollection(Tag::get());
    }
}
