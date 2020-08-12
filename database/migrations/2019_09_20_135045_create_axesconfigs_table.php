<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAxesconfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('axesconfigs', function (Blueprint $table) {
            $table->increments('sensorid');
            $table->string('sensorname',10);
            $table->integer('axeid');
            $table->integer('section');
            $table->integer('conveyor'); // 31-32-41-42
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('axesconfigs');
    }
}
