<?php

namespace App\Http\Controllers;

use App\Http\Forms\InspireSearchForm;
use App\Http\Resources\MiniProjectImagesResourceCollection;
use App\Http\Resources\ProjectImageResource;
use App\Http\Resources\ProjectImageResourceCollection;
use App\Models\ProjectImage;
use App\Modules\SearchEngine\SearchEngineInterface;
use App\Services\ProfessionalService;
use App\Services\ProjectImageService;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    public function __construct(
        private SearchEngineInterface $searchEngine,
        private ProjectImageService $projectImageService,
        private ProfessionalService $professionalService,
        private ProjectService $projectService
    ) {
    }

    public function getImagesBySpace(string $spaces): JsonResponse
    {
        $spaces = explode(',', $spaces);

        $result = collect();
        foreach ($spaces as $space) {
            $result->push([
                'slug' => $space,
                'space' => str_replace('-', ' ', $space),
                'images' => new MiniProjectImagesResourceCollection($this->searchEngine->getImagesBySpace($space, 6))
            ]);
        }

        return new JsonResponse($result);
    }

    public function updateImages(Request $request, string $professionalUid, string $projectId)
    {
        $project = $this->projectService->getById($projectId);
        $professional = $this->professionalService->getByUid($professionalUid);
        if ($this->professionalService->ownProject($professional, $project) === false) {
            abort(403);
        }

        $this->projectImageService->updateImages($project, $request->all());
    }

    public function getImagesBySlug(string $slug): ProjectImageResource
    {
        $image = $this->projectImageService->getImageBySlug($slug);
        if (!$image) {
            abort(404);
        }

        return new ProjectImageResource($image);
    }

    public function inspire(Request $request): MiniProjectImagesResourceCollection
    {
        $searchForm = new InspireSearchForm($request->all());

        $result = $this->searchEngine->inspire($searchForm);

        return new MiniProjectImagesResourceCollection($result);
    }

    public function visited(string $slug): JsonResponse
    {
        $image = $this->projectImageService->getImageBySlug($slug);
        if (!$image) {
            abort(404);
        }

        $this->projectImageService->visited($image);

        return new JsonResponse();
    }
}
