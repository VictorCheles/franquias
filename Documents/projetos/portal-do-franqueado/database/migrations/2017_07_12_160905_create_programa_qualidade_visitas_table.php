<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaQualidadeVisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->create('visitas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formulario_id')->unsigned();
            $table->foreign('formulario_id')->references('id')->on('formularios');

            $table->integer('loja_id')->unsigned();
            $table->foreign('loja_id')->references('id')->on('lojas');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('public.users');

            $table->dateTime('data');
            $table->integer('status');

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
        Schema::connection('consultoria_campo')->drop('visitas');
    }
}
