<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ci');
            $table->string('nombre', 50);
            $table->date('fecha')->nullable();
            $table->time('entrada')->nullable();
            $table->time('salida')->nullable();
            $table->time('tardanza')->nullable();
            $table->time('salioTempr')->nullable();
            $table->time('horaextra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_reports');
    }
}
