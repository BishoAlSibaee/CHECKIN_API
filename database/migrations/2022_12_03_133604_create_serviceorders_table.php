<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceorders', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('roomNumber',false,true);
          $table->integer('Reservation',false,true);
          $table->integer('RorS',false,true);
          $table->integer('Hotel',false,true);
          $table->String('dep',30);
          $table->bigInteger('dateTime',false,true);
          $table->String('orderText');
          $table->integer('status',false,true);
          $table->bigInteger('responseDateTime',false,true);
          $table->integer('responseEmployee',false,true);
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
        Schema::dropIfExists('serviceorders');
    }
}
