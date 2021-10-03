<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnvController extends Controller
{
    public function get(Request $request)
    {
        $name = $request->has('name') ? $request->get('name') : null;

        return $name ? env($name) : file_get_contents(base_path('.env'));
    }
}
