<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAxesconfig56sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('axesconfig56s', function (Blueprint $table) {
            $table->increments('sensorid');
            $table->string('sensorname',10);
            $table->integer('axeid');
            $table->integer('section');
            $table->integer('conveyor'); // 51-52-61-62
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('axesconfig56s');
    }
}
