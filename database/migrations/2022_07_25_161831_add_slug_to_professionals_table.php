<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('slug')->after('id')->nullable()->index();
        });

        foreach (\App\Models\Professional::get() as $professional) {
            $professional->slug = \App\Support\Str::arSlug($professional->company_name) . '-' . rand(9999, 9999999);
            $professional->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
