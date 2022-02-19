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
        // db user sail means it's running in local dev env
        $disk = env('DB_USERNAME') === 'sail' ? 'local-uploads' : 'gcs';

        $categories = json_decode(file_get_contents(__DIR__. '/categories.json'), true);
        foreach ($categories as $category) {
            $image = resource_path("services/$category[id].jpeg");
            $path = Storage::disk($disk)->putFileAs('categories', $image, "$category[id].jpeg");

            $category['slug'] = Str::arSlug($category['name']);
            $category['photo'] = Storage::disk($disk)->url($path);

            $cat = Category::where('id', $category['id'])->first();
            if ($cat) {
                Category::where('id', $cat->id)->update($category);
            } else {
                Category::create($category);
            }
        }
    }
}
