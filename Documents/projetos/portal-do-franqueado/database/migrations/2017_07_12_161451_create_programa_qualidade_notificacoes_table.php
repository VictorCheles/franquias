<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaQualidadeNotificacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->create('notificacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao');
            $table->double('valor_un');
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
        Schema::connection('consultoria_campo')->drop('notificacoes');
    }
}
