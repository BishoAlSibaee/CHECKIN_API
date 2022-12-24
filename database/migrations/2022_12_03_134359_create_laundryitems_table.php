<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaundryitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laundryitems', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Hotel',false,true);
          $table->integer('Facility',false,true);
          $table->string('Name');
          $table->double('Price');
          $table->string('icon');
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
        Schema::dropIfExists('laundryitems');
    }
}
