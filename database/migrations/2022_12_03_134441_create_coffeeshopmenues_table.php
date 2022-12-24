<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoffeeshopmenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coffeeshopmenues', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->integer('facility_id',false,true);
          $table->foreign('facility_id')->references('id')->on('Facilitys');
          $table->string('arabicName');
          $table->string('Name');
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
        Schema::dropIfExists('coffeeshopmenues');
    }
}
