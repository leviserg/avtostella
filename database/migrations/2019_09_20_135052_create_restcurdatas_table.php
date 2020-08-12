<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestcurdatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restcurdatas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sts_o3_1');
            $table->integer('sts_o3_2');
            $table->integer('sts_o4_1');
            $table->integer('sts_o4_2');
            $table->decimal('data_a',7,2);
            $table->decimal('data_b',7,2);
            $table->decimal('data_c',7,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restcurdatas');
    }
}
