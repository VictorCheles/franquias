<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaQualidadePerguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('consultoria_campo')->create('perguntas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pergunta');
            $table->integer('pontuacao');
            $table->integer('topico_id')->unsigned();
            $table->foreign('topico_id')->references('id')->on('topicos');

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
        Schema::connection('consultoria_campo')->drop('perguntas');
    }
}
