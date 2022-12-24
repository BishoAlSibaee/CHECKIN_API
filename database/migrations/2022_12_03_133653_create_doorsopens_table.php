<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoorsopensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doorsopens', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('EmpID',false,true);
          $table->integer('JNum',false,true);
          $table->string('Name',100);
          $table->string('Department',100);
          $table->integer('Room',false,true);
          $table->date('Date');
          $table->time('Time');
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
        Schema::dropIfExists('doorsopens');
    }
}
