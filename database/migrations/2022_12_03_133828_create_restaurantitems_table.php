<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurantitems', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->integer('facility_id',false,true);
          $table->foreign('facility_id')->references('id')->on('facilitys');
          $table->integer('restaurantmenue_id',false,true);
          $table->foreign('restaurantmenue_id')->references('id')->on('restaurantmenues');
          $table->string('menu',40);
          $table->string('name',50);
          $table->string('desc');
          $table->double('price');
          $table->double('descount');
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
        Schema::dropIfExists('restaurantitems');
    }
}
