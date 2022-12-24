<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantorderitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurantorderitems', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('restaurantorder_id',false,true);
          $table->foreign('restaurantorder_id')->references('id')->on('restaurantorders');
          $table->integer('room',false,true);
          $table->integer('itemNo',false,true);
          $table->String('name',100);
          $table->integer('quantity',false,true);
          $table->double('price');
          $table->double('total');
          $table->string('desc');
          $table->string('notes');
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
        Schema::dropIfExists('restaurantorderitems');
    }
}
