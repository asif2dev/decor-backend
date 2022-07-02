<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToProjectImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_images', function (Blueprint $table) {
            $table->string('slug')->after('title')->nullable();
        });

        foreach (\App\Models\ProjectImage::get() as $image) {
            /** @var \App\Models\ProjectImage $image */
            \App\Models\ProjectImage::query()->where('id', $image->id)
                ->update(['slug'=> \App\Support\Str::arSlug($image->title) . '-' . $image->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_images', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
