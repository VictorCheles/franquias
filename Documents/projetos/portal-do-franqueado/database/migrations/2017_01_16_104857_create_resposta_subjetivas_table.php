<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespostaSubjetivasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliador_oculto_respostas_subjetivas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('resposta');
            $table->integer('pergunta_id')->unsigned();
            $table->foreign('pergunta_id')->references('id')->on('avaliador_oculto_perguntas')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('avaliador_oculto_users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('avaliador_oculto_respostas_subjetivas');
    }
}
