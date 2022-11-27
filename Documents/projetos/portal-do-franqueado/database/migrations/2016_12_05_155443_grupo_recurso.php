<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GrupoRecurso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_recurso', function (Blueprint $table) {
            $table->integer('grupo_id')->unsigned();
            $table->foreign('grupo_id', 'fk_grupo_recurso_grupo_id')->references('id')->on('grupos')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('recurso_id')->unsigned();
            $table->foreign('recurso_id', 'fk_grupo_recurso_recurso_id')->references('id')->on('recursos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('grupo_recurso');
    }
}
