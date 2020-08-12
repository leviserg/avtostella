<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStellasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stellas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status'); // 0-Off,1-Fwd,2-Rev,3-Error
            $table->integer('conveyor'); // 51-5a, 52-5B, 61-6A, 62-6B
            $table->integer('position'); // 0-90
            $table->integer('axeid'); // cur axis
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
        Schema::dropIfExists('stellas');
    }
}
