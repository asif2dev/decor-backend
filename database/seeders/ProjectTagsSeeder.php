<?php

namespace Database\Seeders;

use App\Models\ProjectTag;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProjectTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projectTags = json_decode(file_get_contents(__DIR__. '/project-tags.json'), true);
        foreach ($projectTags as $tag) {
            $cat = Tag::where('name', $tag['name'])->first();
            if ($cat) {
                continue;
            }

            Tag::create(['name' => $tag['name']]);
        }
    }
}
