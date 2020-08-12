<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hist_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sensorid');
            $table->integer('convid');
            $table->dateTime('recdate');
            $table->decimal('value', 5, 2);
            $table->integer('validity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hist_data');
    }
}
