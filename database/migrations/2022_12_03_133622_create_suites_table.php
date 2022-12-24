<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suites', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('SuiteNumber',false,true);
          $table->string('Rooms');
          $table->string('RoomsId');
          $table->integer('Hotel',false,true);
          $table->integer('Building',false,true);
          $table->integer('BuildingId',false,true);
          $table->integer('Floor',false,true);
          $table->integer('FloorId',false,true);
          $table->integer('Status',false,true);
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
        Schema::dropIfExists('suites');
    }
}
