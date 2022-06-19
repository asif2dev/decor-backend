<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesSeeder::class);
        $this->call(ProjectTagsSeeder::class);
        $this->call(SpacesTableSeeder::class);
        $this->call(DesignTypesTableSeeder::class);
    }
}
