<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrecaoFkSolicitacaoHistorico2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitacao_historico', function (Blueprint $table) {
            $table->foreign('solicitacao_id')->references('id')->on('solicitacoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitacao_historico', function (Blueprint $table) {
            $table->dropForeign('solicitacao_historico_solicitacao_id_foreign');
        });
    }
}
