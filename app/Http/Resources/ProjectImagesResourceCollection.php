<?php

namespace App\Http\Resources;

use App\Models\ProjectImage;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class ProjectImagesResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array|Arrayable
    {
        if ($this->resource->count() === 0) {
            return [
                [
                    'src' => 'https://storage.googleapis.com/egar_market_assets/no-photo.png',
                    'thumbnail' => 'https://storage.googleapis.com/egar_market_assets/no-photo.png',
                ]
            ];
        }

        return $this->resource->map(
            function (ProjectImage $image) {
                return [
                    'id' => $image->id,
                    'src' => $image->path,
                    'thumbnail' => $image->path
                ];
            }
        );
    }
}
