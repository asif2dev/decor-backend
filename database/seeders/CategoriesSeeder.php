<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

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
            $cat = Category::where('name', $category['name'])->first();
            if ($cat) {
                continue;
            }

            Category::create(['name' => $category['name']]);
        }
    }
}
