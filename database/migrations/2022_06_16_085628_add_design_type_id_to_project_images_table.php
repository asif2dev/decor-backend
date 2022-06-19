<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesignTypeIdToProjectImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_images', function (Blueprint $table) {
            $table->unsignedBigInteger('design_type_id')->after('project_id')->nullable();
            $table->unsignedBigInteger('professional_id')->after('design_type_id')->nullable();
            $table->foreign('design_type_id')->references('id')->on('design_types');
            $table->foreign('professional_id')->references('id')->on('professionals');
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
            $table->dropForeign('project_images_design_type_id_foreign');
            $table->dropColumn('design_type_id');
        });
    }
}
