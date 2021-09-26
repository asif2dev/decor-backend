<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Services\Images\ImageHandlerInterface;
use App\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = json_decode(file_get_contents(__DIR__. '/categories.json'), true);
        foreach ($categories as $category) {
            $cat = Category::where('id', $category['id'])->first();
            if ($cat) {
                continue;
            }

            $image = resource_path("services/$category[id].jpeg");
            $path = Storage::putFile('categories', new File($image));

            $category['slug'] = Str::arSlug($category['name']);
            $category['photo'] = Storage::url($path);

            Category::create($category);
        }
    }
}
