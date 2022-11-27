<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AvaliadorOcultoRespostasLojaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avaliador_oculto_respostas', function (Blueprint $table) {
            $table->integer('loja_id')->unsigned();
            $table->foreign('loja_id', 'fk_respostas_loja_id')->references('id')->on('lojas')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('avaliador_oculto_respostas_subjetivas', function (Blueprint $table) {
            $table->integer('loja_id')->unsigned();
            $table->foreign('loja_id', 'fk_respostas_subjetivas_loja_id')->references('id')->on('lojas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avaliador_oculto_respostas', function (Blueprint $table) {
            $table->dropForeign('fk_respostas_loja_id');
            $table->dropColumn('loja_id');
        });

        Schema::table('avaliador_oculto_respostas_subjetivas', function (Blueprint $table) {
            $table->dropForeign('fk_respostas_subjetivas_loja_id');
            $table->dropColumn('loja_id');
        });
    }
}
