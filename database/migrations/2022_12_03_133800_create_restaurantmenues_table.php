<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantmenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurantmenues', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->integer('FacilityId',false,true);
          $table->string('name',50);
          $table->string('arabicName',50);
          $table->string('photo');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurantmenues');
    }
}
