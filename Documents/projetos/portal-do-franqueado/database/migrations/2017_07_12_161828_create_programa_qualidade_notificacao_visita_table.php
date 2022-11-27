<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaQualidadeNotificacaoVisitaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->create('notificacao_visita', function (Blueprint $table) {
            $table->unsignedBigInteger('notificacao_id')->unsigned();
            $table->integer('visita_id')->unsigned();
            $table->integer('quantidade');
            $table->double('valor_un');

            $table->foreign('notificacao_id')->references('id')->on('notificacoes');
            $table->foreign('visita_id')->references('id')->on('visitas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('consultoria_campo')->drop('notificacao_visita');
    }
}
