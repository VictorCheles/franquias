<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AvaliadorOcultoPerguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliador_oculto_perguntas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('pergunta');
            $table->integer('formulario_id')->unsigned();
            $table->foreign('formulario_id')->references('id')->on('avaliador_oculto_formularios')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::drop('avaliador_oculto_perguntas');
    }
}
