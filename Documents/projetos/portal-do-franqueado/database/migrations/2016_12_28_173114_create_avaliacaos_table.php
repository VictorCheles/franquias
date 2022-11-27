<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvaliacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('titulo');
            $table->integer('avaliacao_categoria_id')->unsigned();
            $table->foreign('avaliacao_categoria_id')->references('id')->on('avaliacoes_categorias');
            $table->integer('avaliacao_setor_id')->unsigned();
            $table->foreign('avaliacao_setor_id')->references('id')->on('avaliacoes_setores');
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
        Schema::drop('avaliacoes');
    }
}
