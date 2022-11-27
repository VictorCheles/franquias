<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePersonalizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personificacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ator_id')->unsigned();
            $table->foreign('ator_id')->references('id')->on('users');
            $table->integer('personagem_id')->unsigned();
            $table->foreign('personagem_id')->references('id')->on('users');
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
        Schema::drop('personificacoes');
    }
}
