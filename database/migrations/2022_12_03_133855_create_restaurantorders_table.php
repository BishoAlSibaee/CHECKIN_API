<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurantorders', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->integer('Facility',false,true);
          $table->integer('Reservation',false,true);
          $table->integer('room',false,true);
          $table->integer('RorS',false,true);
          $table->integer('roomId',false,true);
          $table->integer('dateTime',false,true);
          $table->double('total');
          $table->integer('status',false,true);
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
        Schema::dropIfExists('restaurantorders');
    }
}
