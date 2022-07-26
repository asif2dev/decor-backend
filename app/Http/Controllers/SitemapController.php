<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;

class SitemapController extends Controller
{
    public function get()
    {
        $professionals = Professional::all();
        $projects = Project::all();
        $images = ProjectImage::all();

        $urls = [];
        foreach ($professionals as $professional) {
            $urls[] = '/professional/' . $professional->slug;
        }

        foreach ($projects as $project) {
            $urls[] = '/projects/' . $project->id;
        }

        foreach ($images as $image) {
            $urls[] = '/images/' . $image->slug;
        }

        return new JsonResponse($urls);
    }
}
