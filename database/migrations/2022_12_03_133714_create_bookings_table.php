<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('RoomNumber',false,true);
          $table->integer('ClientId',false,true);
          $table->integer('Status',false,true);
          $table->integer('RoomOrSuite',false,true);
          $table->integer('MultiRooms',false,true);
          $table->string('AddRoomNumber',100);
          $table->string('AddRoomId',100);
          $table->date('StartDate');
          $table->integer('Nights',false,true);
          $table->date('EndDate');
          $table->integer('Hotel',false,true);
          $table->integer('BuildingNo',false,true);
          $table->integer('Floor',false,true);
          $table->string('ClientFirstName',40);
          $table->string('ClientLastName',40);
          $table->enum('IdType',['ID','PASSPORT']);
          $table->integer('IdNumber',false,true);
          $table->integer('MobileNumber',false,true);
          $table->string('Email',100);
          $table->float('Rating',1,1);
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
        Schema::dropIfExists('bookings');
    }
}
