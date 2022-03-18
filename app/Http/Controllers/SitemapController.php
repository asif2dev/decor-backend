<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class SitemapController extends Controller
{
    public function get()
    {
        $professionals = Professional::all();
        $projects = Project::all();

        $urls = [];
        foreach ($professionals as $professional) {
            $urls[] = '/professional/' . $professional->uid;
        }

        foreach ($projects as $project) {
            $urls[] = '/projects/' . $project->id;
        }

        return new JsonResponse($urls);
    }
}
