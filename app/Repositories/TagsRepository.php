<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class TagsRepository extends BaseRepository
{
    public function syncTags(array $tagNames): Collection
    {
        /** @var Collection $existingTags */
        $existingTags = Tag::whereIn('name', $tagNames)->get();

        $newTags = array_diff($tagNames, $existingTags->pluck('name')->flatten()->toArray());

        $newTags = array_map(
            fn($name) => ['name' => $name, 'created_at' => now(), 'updated_at' => now()],
            $newTags
        );

        Tag::insert($newTags);

        return Tag::whereIn('name', $tagNames)->get();
    }
}
