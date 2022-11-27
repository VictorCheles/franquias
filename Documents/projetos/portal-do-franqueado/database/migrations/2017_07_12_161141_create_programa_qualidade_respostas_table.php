<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaQualidadeRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->create('respostas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('visita_id')->unsigned();
            $table->integer('pergunta_id')->unsigned();
            $table->boolean('resposta');
            $table->integer('pontuacao');
            $table->json('fotos');

            $table->foreign('visita_id')->references('id')->on('visitas');
            $table->foreign('pergunta_id')->references('id')->on('perguntas');

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
        Schema::connection('consultoria_campo')->drop('respostas');
    }
}
