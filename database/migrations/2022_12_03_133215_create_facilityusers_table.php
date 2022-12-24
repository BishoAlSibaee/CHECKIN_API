<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilityusers', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('facility_id',false,true);
          $table->foreign('facility_id')->references('id')->on('facilitys');
          $table->string('UserName',40);
          $table->string('Password',50);
          $table->string('Name',100);
          $table->string('Mobile',15);
          $table->string('token');
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
        Schema::dropIfExists('facilityusers');
    }
}
