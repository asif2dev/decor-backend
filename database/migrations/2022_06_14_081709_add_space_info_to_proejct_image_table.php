<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpaceInfoToProejctImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_images', function (Blueprint $table) {
            $table->string('title')->after('id')->nullable();
            $table->string('description', 1000)->after('title')->nullable();
            $table->string('palette')->after('description')->nullable();
            $table->unsignedBigInteger('space_id')->nullable()->after('description');

            $table->foreign('space_id')->references('id')->on('spaces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_images', function (Blueprint $table) {
            //
        });
    }
}
