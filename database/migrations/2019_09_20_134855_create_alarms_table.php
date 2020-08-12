<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descr',100);
            $table->integer('categ'); // 1-alarm / 2-warning / 3-event - join on alarmcategory table
            $table->integer('status'); // 1- unack / 2- ack
            $table->dateTime('appeared');
            $table->dateTime('acknowledged')->nullable();
            $table->string('acknowledgedby',30)->nullable();
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
        Schema::dropIfExists('alarms');
    }
}
