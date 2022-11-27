<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaQualidadeAcoesCorretivasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->create('acoes_corretivas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('visita_id')->unsigned();
            $table->string('descricao');
            $table->dateTime('data_correcao');
            $table->integer('status');
            $table->integer('pergunta_id')->unsigned();

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
        Schema::connection('consultoria_campo')->drop('acoes_corretivas');
    }
}
