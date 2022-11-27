<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventoCalendariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_calendario', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fim')->nullable();
            $table->string('relacao');
            $table->integer('relacao_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('evento_calendario');
    }
}
