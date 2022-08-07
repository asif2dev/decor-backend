<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewsCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->mediumInteger('views_count')->after('slug')->default(0);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->mediumInteger('views_count')->after('slug')->default(0);
        });

        Schema::table('project_images', function (Blueprint $table) {
            $table->mediumInteger('views_count')->after('slug')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });

        Schema::table('project_images', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });
    }
}
