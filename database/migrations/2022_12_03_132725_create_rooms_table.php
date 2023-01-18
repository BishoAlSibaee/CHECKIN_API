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
          $table->increments('id'); //1
          $table->integer('RoomNumber',false,true); //2
          $table->integer('Status',false,true)->default(0); //3
          $table->integer('hotel',false,true)->default(0); //4
          $table->integer('Building',false,true); //5
          $table->integer('building_id',false,true); //6
          $table->foreign('building_id')->references('id')->on('buildings'); //7
          $table->integer('Floor',false,true); //8
          $table->integer('floor_id',false,true); //9
          $table->foreign('floor_id')->references('id')->on('floors'); //10
          $table->string('RoomType',30)->default(''); //11
          $table->integer('SuiteStatus',false,true)->default(0); //12
          $table->integer('SuiteNumber',false,true)->default(0); //13
          $table->integer('SuiteId',false,true)->default(0); //14
          $table->integer('ReservationNumber',false,true)->default(0); //15
          $table->integer('roomStatus',false,true)->default(1); //16
          $table->integer('Tablet',false,true)->default(0); //17
          $table->string('dep',30)->default('0'); //18
          $table->bigInteger('Cleanup',false,true)->default(0); //19
          $table->bigInteger('Laundry',false,true)->default(0); //20
          $table->bigInteger('RoomService',false,true)->default(0); //21
          $table->string('RoomServiceText')->default(''); //22
          $table->bigInteger('Checkout',false,true)->default(0); //23
          $table->bigInteger('Restaurant',false,true)->default(0); //24
          $table->bigInteger('MiniBarCheck',false,true)->default(0); //25
          $table->integer('Facility',false,true)->default(0); //26
          $table->bigInteger('SOS',false,true)->default(0); //27
          $table->bigInteger('DND',false,true)->default(0); //28
          $table->integer('PowerSwitch',false,true)->default(0); //29
          $table->integer('DoorSensor',false,true)->default(0); //30
          $table->integer('MotionSensor',false,true)->default(0); //31
          $table->integer('Thermostat',false,true)->default(0);//32
          $table->integer('ZBGateway',false,true)->default(0); //33
          $table->integer('online',false,true)->default(0); //34
          $table->integer('CurtainSwitch',false,true)->default(0); //35
          $table->integer('ServiceSwitch',false,true)->default(0); //36
          $table->integer('lock',false,true)->default(0); //37
          $table->integer('Switch1',false,true)->default(0); //38
          $table->integer('Switch2',false,true)->default(0); //39
          $table->integer('Switch3',false,true)->default(0); //40
          $table->integer('Switch4',false,true)->default(0); //41
          $table->string('LockGateway',40)->default(''); //42
          $table->string('LockName',40)->default(''); //43
          $table->integer('powerStatus',false,true)->default(0); //44
          $table->integer('curtainStatus',false,true)->default(0); //45
          $table->integer('doorStatus',false,true)->default(0); //46
          $table->integer('DoorWarning',false,true)->default(0); //47
          $table->integer('temp',false,true)->default(0); //48
          $table->integer('TempSetPoint',false,true)->default(25); //49
          $table->integer('SetPointInterval',false,true)->default(10); //50
          $table->integer('CheckInModeTime',false,true)->default(1); //51
          $table->integer('CheckOutModeTime',false,true)->default(1); //52
          $table->string('WelcomeMessage')->default('welcome *G'); //53
          $table->string('Logo')->default(''); //54
          $table->string('token')->default(''); //55
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
