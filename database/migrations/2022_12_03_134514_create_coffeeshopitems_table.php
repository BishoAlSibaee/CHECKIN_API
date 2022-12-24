<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoffeeshopitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coffeeshopitems', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->integer('facility_id',false,true);
          $table->foreign('facility_id')->references('id')->on('facilitys');
          $table->integer('coffeeshopmenue_id',false,true);
          $table->foreign('coffeeshopmenue_id')->references('id')->on('coffeeshopmenues');
          $table->string('Menu');
          $table->string('Name');
          $table->string('Desc');
          $table->double('Price');
          $table->double('Discount');
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
        Schema::dropIfExists('coffeeshopitems');
    }
}
