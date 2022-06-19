<?php

namespace App\Http\Controllers;

use App\Http\Forms\InspireSearchForm;
use App\Http\Resources\ProjectImagesResourceCollection;
use App\Modules\SearchEngine\SearchEngineInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    public function __construct(private SearchEngineInterface $searchEngine)
    {
    }

    public function getImagesBySpace(string $spaces): JsonResponse
    {
        $spaces = explode(',', $spaces);

        $result = collect();
        foreach ($spaces as $space) {
            $result->push([
                'slug' => $space,
                'space' => str_replace('-', ' ', $space),
                'images' => new ProjectImagesResourceCollection($this->searchEngine->getImagesBySpace($space))
            ]);
        }

        return new JsonResponse($result);
    }

    public function inspire(Request $request)
    {
        $searchForm = new InspireSearchForm($request->all());

        $result = $this->searchEngine->inspire($searchForm);

        return new ProjectImagesResourceCollection($result);
    }
}
