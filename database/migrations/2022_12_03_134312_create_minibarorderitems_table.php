<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinibarorderitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minibarorderitems', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('ItemId',false,true);
          $table->integer('minibarorder_id',false,true);
          $table->foreign('minibarorder_id')->references('id')->on('minibarorders');
          $table->integer('Reservation',false,true);
          $table->integer('Room',false,true);
          $table->string('Name');
          $table->double('Price');
          $table->integer('Quantity',false,true);
          $table->double('Total');
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
        Schema::dropIfExists('minibarorderitems');
    }
}
