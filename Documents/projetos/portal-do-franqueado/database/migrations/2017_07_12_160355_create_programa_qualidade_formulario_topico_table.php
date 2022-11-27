<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaQualidadeFormularioTopicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->create('formulario_topico', function (Blueprint $table) {
            $table->integer('formulario_id')->unsigned();
            $table->integer('topico_id')->unsigned();

            $table->foreign('formulario_id')->references('id')->on('formularios');
            $table->foreign('topico_id')->references('id')->on('topicos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('consultoria_campo')->drop('formulario_topico');
    }
}
