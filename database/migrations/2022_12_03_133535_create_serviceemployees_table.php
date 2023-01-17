<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceemployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceemployees', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('projectId',false,true);
          $table->string('name',100);
          $table->integer('jobNumber',false,true);
          $table->string('password');
          $table->string('department',40);
          $table->integer('mobile',false,true);
          $table->string('token')->default('0');
          $table->string('mytoken');
          $table->string('control')->default('all');
          $table->integer('logedin')->default(0);
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
        Schema::dropIfExists('serviceemployees');
    }
}
