<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinibaritemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minibaritems', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->integer('facility_id',false,true);
          $table->foreign('facility_id')->references('id')->on('Facilitys');
          $table->string('Name');
          $table->double('Price');
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
        Schema::dropIfExists('minibaritems');
    }
}
