<?php

namespace Database\Seeders;

use App\Models\Space;
use App\Support\Str;
use Illuminate\Database\Seeder;

class SpacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spaces = [
            'مطبخ',
            'حمام',
            'غرفة نوم',
            'غرفة معيشة',
            'تناول الطعام',
            'أماكن مفتوحة',
            'غرف أطفال',
            'سلم',
            'نادي رياضي',
            'قاعة',
            'مكتب',
            'التخزين',
            'تصمميم خارجي',
            'مدخل',
            'المناظر الطبيعيه و لاندسكيب',
            'كافيه',
            'مطعم',
            'طبي',
            'الحدائق والساحات الخلفية',
            'حمام سباحة',
            'كلاسيك',
            'نيو كلاسيك'
        ];

        foreach ($spaces as $space) {
            Space::query()->updateOrCreate(['name' => $space, 'slug' => Str::arSlug($space)]);
        }
    }
}
