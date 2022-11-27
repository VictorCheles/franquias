<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLojasFormularios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliador_oculto_lojas_formularios', function (Blueprint $table) {
            $table->integer('loja_id')->unsigned();
            $table->foreign('loja_id')->references('id')->on('lojas')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('formulario_id')->unsigned();
            $table->foreign('formulario_id')->references('id')->on('avaliador_oculto_formularios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('avaliador_oculto_lojas_formularios');
    }
}
