<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectvariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectsvariables', function (Blueprint $table) {
          $table->increments('id');
            $table->integer('Hotel',false,true);
            $table->integer('Temp',false,true);
            $table->integer('Interval',false,true);
            $table->integer('DoorWarning',false,true);
            $table->integer('CheckInModeTime',false,true);
            $table->integer('CheckOutModeTime',false,true);
            $table->string('WelcomeMessage');
            $table->string('Logo');
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
        Schema::dropIfExists('projectsvariables');
    }
}
