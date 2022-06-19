<?php

namespace Database\Seeders;

use App\Models\DesignType;
use App\Support\Str;
use Illuminate\Database\Seeder;

class DesignTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designTypes = json_decode(file_get_contents(__DIR__. '/design-types.json'), true);
        foreach ($designTypes as $designType) {
            $slug = Str::arSlug($designType['name']);
            DesignType::query()->updateOrCreate(['slug' => $slug, 'name' => $designType['name']]);
        }
    }
}
