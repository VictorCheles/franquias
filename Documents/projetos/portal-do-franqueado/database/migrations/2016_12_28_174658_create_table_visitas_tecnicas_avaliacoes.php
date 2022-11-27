<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVisitasTecnicasAvaliacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitas_tecnicas_avaliacoes', function (Blueprint $table) {
            $table->integer('visita_tecnica_id')->unsigned();
            $table->foreign('visita_tecnica_id')->references('id')->on('visitas_tecnicas')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('avaliacao_id')->unsigned();
            $table->foreign('avaliacao_id')->references('id')->on('avaliacoes')->onUpdate('cascade')->onDelete('cascade');
            $table->text('observacoes')->nullable();
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('visitas_tecnicas_avaliacoes');
    }
}
