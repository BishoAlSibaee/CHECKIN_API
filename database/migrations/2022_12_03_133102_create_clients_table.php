<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->string('FirstName',50);
          $table->string('LastName',50);
          $table->string('Mobile');
          $table->string('Email')->nullable(true);
          $table->enum('IdType',['ID','PASSPORT']);
          $table->string('IdNumber',20);
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
        Schema::dropIfExists('clients');
    }
}
