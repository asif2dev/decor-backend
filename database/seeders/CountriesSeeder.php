<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Country::truncate();

        Country::create(
            ['name' => 'مصر']
        );


        City::truncate();
        $cities = json_decode(file_get_contents(__DIR__ . '/citiies.json'), true);
        foreach ($cities as $city) {
            City::create(['name' => $city['governorate_name_ar'], 'country_id' => 1]);
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
