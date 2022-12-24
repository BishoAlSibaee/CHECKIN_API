<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('RoomNumber',false,true);
          $table->integer('Status',false,true)->default(0);
          $table->integer('hotel',false,true)->default(0);
          $table->integer('Building',false,true);
          $table->integer('building_id',false,true);
          $table->foreign('building_id')->references('id')->on('buildings');
          $table->integer('Floor',false,true);
          $table->integer('floor_id',false,true);
          $table->foreign('floor_id')->references('id')->on('floors');
          $table->string('RoomType',30)->default('');
          $table->integer('SuiteStatus',false,true)->default(0);
          $table->integer('SuiteNumber',false,true)->default(0);
          $table->integer('SuiteId',false,true)->default(0);
          $table->integer('ReservationNumber',false,true)->default(0);
          $table->integer('roomStatus',false,true)->default(1);
          $table->integer('Tablet',false,true)->default(0);
          $table->string('dep',30)->default('');
          $table->integer('Cleanup',false,true)->default(0);
          $table->integer('Laundry',false,true)->default(0);
          $table->integer('RoomService',false,true)->default(0);
          $table->string('RoomServiceText')->default('');
          $table->integer('Checkout',false,true)->default(0);
          $table->integer('Restaurant',false,true)->default(0);
          $table->integer('MiniBarCheck',false,true)->default(0);
          $table->integer('Facility',false,true)->default(0);
          $table->integer('SOS',false,true)->default(0);
          $table->integer('DND',false,true)->default(0);
          $table->integer('PowerSwitch',false,true)->default(0);
          $table->integer('DoorSensor',false,true)->default(0);
          $table->integer('MotionSensor',false,true)->default(0);
          $table->integer('Thermostat',false,true)->default(0);
          $table->integer('ZBGateway',false,true)->default(0);
          $table->integer('CurtainSwitch',false,true)->default(0);
          $table->integer('ServiceSwitch',false,true)->default(0);
          $table->integer('lock',false,true)->default(0);
          $table->integer('Switch1',false,true)->default(0);
          $table->integer('Switch2',false,true)->default(0);
          $table->integer('Switch3',false,true)->default(0);
          $table->integer('Switch4',false,true)->default(0);
          $table->string('LockGateway',40)->default('');
          $table->string('LockName',40)->default('');
          $table->integer('powerStatus',false,true)->default(0);
          $table->integer('curtainStatus',false,true)->default(0);
          $table->integer('doorStatus',false,true)->default(0);
          $table->integer('DoorWarning',false,true)->default(0);
          $table->integer('temp',false,true)->default(0);
          $table->integer('TempSetPoint',false,true)->default(25);
          $table->integer('SetPointInterval',false,true)->default(10);
          $table->integer('CheckInModeTime',false,true)->default(1);
          $table->integer('CheckOutModeTime',false,true)->default(1);
          $table->string('WelcomeMessage')->default('welcome *G');
          $table->string('Logo')->default('');
          $table->string('token')->default('');
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
        Schema::dropIfExists('rooms');
    }
}
