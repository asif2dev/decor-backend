<?php

namespace App\Repositories;

use App\Models\ProjectImage;

class ProjectImageRepository extends BaseRepository
{
    public function getBySlug(string $slug): ?ProjectImage
    {
        return ProjectImage::query()->where('slug', $slug)->first();
    }
}
