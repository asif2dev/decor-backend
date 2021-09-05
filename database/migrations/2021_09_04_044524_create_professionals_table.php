<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uid')->unique();
            $table->string('company_name');
            $table->string('logo')->nullable();
            $table->text('about');
            $table->unsignedBigInteger('category_id');
            $table->boolean('offer_execution')->default(false);
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('lat_lng');
            $table->string('full_address');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professionals');
    }
}
