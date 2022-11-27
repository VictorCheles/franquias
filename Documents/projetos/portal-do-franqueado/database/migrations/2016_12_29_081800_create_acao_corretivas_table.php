<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcaoCorretivasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acoes_corretivas', function (Blueprint $table) {
            $table->integer('visita_tecnica_id')->unsigned();
            $table->foreign('visita_tecnica_id')->references('id')->on('visitas_tecnicas');
            $table->integer('avaliacao_id')->unsigned();
            $table->foreign('avaliacao_id')->references('id')->on('avaliacoes');
            $table->text('descricao');
            $table->date('prazo');
            $table->date('data_correcao')->nullable();
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
        Schema::drop('acoes_corretivas');
    }
}
