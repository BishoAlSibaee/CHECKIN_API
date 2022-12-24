<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otherinvoices', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('Room',false,true);
          $table->integer('Reservation',false,true);
          $table->integer('InvoiceNumber',false,true);
          $table->string('InvoiceType');
          $table->date('Date');
          $table->double('Total');
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
        Schema::dropIfExists('otherinvoices');
    }
}
