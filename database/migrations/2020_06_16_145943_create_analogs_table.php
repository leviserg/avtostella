<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('conveyor'); // 51, 52, 61, 62
            $table->integer('ai');
            $table->decimal('level', 5, 2);
        });

        // php artisan migrate:refresh --path=/database/migrations/2020_06_16_145943_create_analogs_table.php --seed
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analogs');
    }
}
